<?php

namespace App\Services;

use App\Models\DaySession;
use App\Models\Terminal;
use App\Models\Transaction;
use App\Models\ZReading;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ZReadingService
{
    public function __construct(
        protected DaySessionService $daySessionService
    ) {}

    /**
     * Generate Z-Reading for the given day session. Closes the day session and increments z_counter.
     *
     * @param  array{cash_count?: array, amount_submitted?: float, pull_outs?: float, store_manager_name?: string}  $options
     */
    public function generate(DaySession $daySession, array $options = []): ZReading
    {
        return DB::transaction(function () use ($daySession, $options) {
            $branchId = (int) $daySession->branch_id;
            $terminalId = (int) $daySession->terminal_id;

            $completed = Transaction::where('day_session_id', $daySession->id)
                ->where('status', 'completed')
                ->with(['discounts', 'officialReceipt', 'transactionPayments', 'items'])
                ->get();

            $voidQuery = Transaction::where('day_session_id', $daySession->id)->where('status', 'voided');
            $voidCount = $voidQuery->count();
            $voidTransAmount = (float) $voidQuery->sum('total');

            $returnedCount = Transaction::where('day_session_id', $daySession->id)->where('status', 'refunded')->count();
            $totalReturns = (float) Transaction::where('day_session_id', $daySession->id)->where('status', 'refunded')->sum('total');

            $grossSales = $completed->sum('total');
            $totalDiscounts = $completed->sum('discount_amount');
            $netSales = round($grossSales - $totalDiscounts - $totalReturns, 2);

            $vatAmount = $completed->sum('vat_amount');
            $vatableSales = $completed->sum(fn ($t) => (float) ($t->officialReceipt->vatable_sales ?? 0));
            if ($vatableSales == 0 && $grossSales > 0) {
                $vatableSales = round($grossSales / 1.12, 2);
            }
            $vatExempt = $completed->sum(fn ($t) => (float) ($t->officialReceipt->vat_exempt ?? 0));
            $zeroRated = 0;

            $scDiscount = 0;
            $pwdDiscount = 0;
            foreach ($completed as $t) {
                foreach ($t->discounts as $d) {
                    $type = $d->type ?? '';
                    $amt = (float) $d->amount;
                    if (in_array($type, ['sc_pwd', 'senior_citizen'], true)) {
                        $scDiscount += $amt;
                    } elseif ($type === 'pwd') {
                        $pwdDiscount += $amt;
                    }
                }
            }
            $promoDiscount = round($totalDiscounts - $scDiscount - $pwdDiscount, 2);
            if ($promoDiscount < 0) {
                $promoDiscount = 0;
            }
            $scVat = round($scDiscount * 0.12 / 1.12, 2);
            $pwdVat = round($pwdDiscount * 0.12 / 1.12, 2);

            $itemsSold = $completed->sum(fn ($t) => $t->items->sum('quantity'));

            $cashTotal = 0;
            $cardTotal = 0;
            $ewalletTotal = 0;
            $hmoTotal = 0;
            $splitTotal = 0;
            foreach ($completed as $t) {
                if ($t->payment_method === 'split' && $t->transactionPayments->isNotEmpty()) {
                    foreach ($t->transactionPayments as $p) {
                        $amt = (float) $p->amount;
                        switch ($p->payment_method) {
                            case 'cash': $cashTotal += $amt; break;
                            case 'card': $cardTotal += $amt; break;
                            case 'ewallet': $ewalletTotal += $amt; break;
                            case 'hmo': $hmoTotal += $amt; break;
                            default: $splitTotal += $amt;
                        }
                    }
                } else {
                    $amt = (float) $t->total;
                    switch ($t->payment_method) {
                        case 'cash': $cashTotal += $amt; break;
                        case 'card': $cardTotal += $amt; break;
                        case 'ewallet': $ewalletTotal += $amt; break;
                        case 'hmo': $hmoTotal += $amt; break;
                        case 'split': $splitTotal += $amt; break;
                        default: $cashTotal += $amt;
                    }
                }
            }

            $orNumbers = $completed->pluck('or_number')->filter();
            $orStart = $orNumbers->min();
            $orEnd = $orNumbers->max();

            $changeFund = (float) ($daySession->opening_cash ?? 0);
            $pullOuts = (float) ($options['pull_outs'] ?? 0);
            $totalInDrawer = $netSales + $changeFund - $totalReturns - $pullOuts;

            // Amount Submitted = from cash count (total of denominations) or explicitly entered
            $amountSubmitted = isset($options['amount_submitted']) ? (float) $options['amount_submitted'] : null;
            if ($amountSubmitted === null && ! empty($options['cash_count']) && is_array($options['cash_count'])) {
                $denomValues = ['1000' => 1000, '500' => 500, '200' => 200, '100' => 100, '50' => 50, '20' => 20, '10' => 10, '5' => 5, '1' => 1, '0.25' => 0.25, '0.10' => 0.10, '0.05' => 0.05, '0.01' => 0.01];
                $cashCountTotal = 0;
                foreach ($denomValues as $key => $value) {
                    $qty = isset($options['cash_count'][$key]) ? (int) $options['cash_count'][$key] : 0;
                    $cashCountTotal += $qty * $value;
                }
                $amountSubmitted = round($cashCountTotal, 2);
            }
            // Amount Over = Amount Submitted − Total In Drawer (over/short)
            $amountOver = $amountSubmitted !== null ? round($amountSubmitted - $totalInDrawer, 2) : null;

            $terminal = Terminal::find($terminalId);
            $oldAccumulated = $terminal ? (float) $terminal->accumulated_sales : 0;
            $newAccumulated = $oldAccumulated + $netSales;

            $zCounter = $terminal ? ((int) $terminal->z_counter) + 1 : 1;

            $sessionDate = $daySession->session_date;
            $periodFrom = $sessionDate->copy()->startOfDay();
            $periodTo = now();

            $zReading = ZReading::create([
                'branch_id' => $branchId,
                'terminal_id' => $terminalId,
                'day_session_id' => $daySession->id,
                'z_counter' => $zCounter,
                'or_series_start' => $orStart,
                'or_series_end' => $orEnd,
                'total_transactions' => $completed->count(),
                'void_transactions' => $voidCount,
                'void_trans_amount' => $voidTransAmount,
                'returned_transactions' => $returnedCount,
                'items_sold' => $itemsSold,
                'gross_sales' => $grossSales,
                'total_discounts' => $totalDiscounts,
                'total_returns' => $totalReturns,
                'net_sales' => $netSales,
                'vatable_sales' => $vatableSales,
                'vat_amount' => $vatAmount,
                'vat_exempt' => $vatExempt,
                'zero_rated' => $zeroRated,
                'sc_discount' => $scDiscount,
                'sc_vat' => $scVat,
                'pwd_discount' => $pwdDiscount,
                'pwd_vat' => $pwdVat,
                'promo_discount' => $promoDiscount,
                'cash_total' => $cashTotal,
                'card_total' => $cardTotal,
                'ewallet_total' => $ewalletTotal,
                'hmo_total' => $hmoTotal,
                'split_total' => $splitTotal,
                'change_fund' => $changeFund,
                'pull_outs' => $pullOuts,
                'amount_submitted' => $amountSubmitted,
                'amount_over' => $amountOver,
                'old_accumulated_sales' => $oldAccumulated,
                'new_accumulated_sales' => $newAccumulated,
                'store_manager_name' => $options['store_manager_name'] ?? null,
                'reporting_date' => $sessionDate,
                'period_from' => $periodFrom,
                'period_to' => $periodTo,
                'cash_count' => $options['cash_count'] ?? null,
            ]);

            $daySession->update(['or_series_start' => $orStart, 'or_series_end' => $orEnd]);
            $this->daySessionService->closeDaySession($daySession, (int) $zReading->id, Auth::id());

            if ($terminal) {
                $terminal->increment('z_counter');
                $terminal->update(['accumulated_sales' => $newAccumulated]);
            }

            return $zReading;
        });
    }
}

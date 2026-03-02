<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\XReading;
use Illuminate\Support\Facades\Auth;

class XReadingService
{
    /**
     * Generate an X-Reading (mid-day snapshot). Does NOT reset any counters.
     * BIR-aligned: items_sold, void_trans_amount, sales_batch_no, cashier accountability.
     *
     * @param  array{change_fund?: float, pull_outs?: float, amount_submitted?: float, administrator_name?: string}  $options
     */
    public function generate(int $branchId, ?int $terminalId, array $options = []): XReading
    {
        $periodFrom = now()->startOfDay();
        $periodTo = now();

        $completed = Transaction::where('branch_id', $branchId)
            ->where('status', 'completed')
            ->when($terminalId, fn ($q) => $q->where('terminal_id', $terminalId))
            ->whereBetween('created_at', [$periodFrom, $periodTo])
            ->with(['discounts', 'officialReceipt', 'transactionPayments', 'items'])
            ->get();

        $voidQuery = Transaction::where('branch_id', $branchId)
            ->where('status', 'voided')
            ->when($terminalId, fn ($q) => $q->where('terminal_id', $terminalId))
            ->whereBetween('created_at', [$periodFrom, $periodTo]);
        $voidCount = $voidQuery->count();
        $voidTransAmount = (float) $voidQuery->sum('total');

        $returnedCount = Transaction::where('branch_id', $branchId)
            ->where('status', 'refunded')
            ->when($terminalId, fn ($q) => $q->where('terminal_id', $terminalId))
            ->whereBetween('created_at', [$periodFrom, $periodTo])
            ->count();

        $grossSales = $completed->sum('total');
        $totalDiscounts = $completed->sum('discount_amount');
        $totalReturns = 0;
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

        // BIR: VAT attributable to SC/PWD (VAT waived on discounted portion; approximate)
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

        $salesBatchNo = (string) (XReading::where('branch_id', $branchId)
            ->whereDate('created_at', $periodFrom->toDateString())
            ->count() + 1);
        $salesBatchNo = str_pad($salesBatchNo, 10, '0', STR_PAD_LEFT);

        $changeFund = (float) ($options['change_fund'] ?? 0);
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
        // Amount Over = Amount Submitted − Total In Drawer
        $amountOver = $amountSubmitted !== null ? round($amountSubmitted - $totalInDrawer, 2) : null;

        return XReading::create([
            'branch_id' => $branchId,
            'cashier_id' => Auth::id(),
            'terminal_id' => $terminalId,
            'shift' => 'day',
            'or_series_start' => $orStart,
            'or_series_end' => $orEnd,
            'sales_batch_no' => $salesBatchNo,
            'total_transactions' => $completed->count(),
            'void_transactions' => $voidCount,
            'void_trans_amount' => $voidTransAmount,
            'returned_transactions' => $returnedCount,
            'items_sold' => $itemsSold,
            'gross_sales' => $grossSales,
            'total_discounts' => $totalDiscounts,
            'total_returns' => $totalReturns,
            'net_sales' => $netSales,
            'price_quotes' => 0,
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
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
            'administrator_name' => $options['administrator_name'] ?? null,
            'cash_count' => $options['cash_count'] ?? null,
        ]);
    }
}

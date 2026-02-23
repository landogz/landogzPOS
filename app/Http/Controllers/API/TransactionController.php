<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Transaction::query()->with(['cashier', 'items.product']);
        $branchId = $request->get('branch_id');
        $userBranchId = $user?->branch_id;
        $canChooseBranch = $user && in_array($user->role, ['super_admin', 'admin'], true);
        if ($branchId !== null && $branchId !== '' && $canChooseBranch) {
            $query->where('branch_id', (int) $branchId);
        } elseif ($userBranchId !== null) {
            $query->where('branch_id', $userBranchId);
        } elseif ($user && $user->role === 'admin' && $user->company_id) {
            $companyBranchIds = Branch::where('company_id', $user->company_id)->pluck('id')->all();
            if (!empty($companyBranchIds)) {
                $query->whereIn('branch_id', $companyBranchIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }
        $transactions = $query->latest()->paginate($request->get('per_page', 15));
        return response()->json(['status' => true, 'data' => $transactions]);
    }

    public function show(Request $request, Transaction $transaction): JsonResponse
    {
        $user = $request->user();
        if ($user && $user->role === 'admin' && $user->company_id) {
            $transaction->load('branch');
            if (! $transaction->branch || (int) $transaction->branch->company_id !== (int) $user->company_id) {
                abort(404, 'Transaction not found.');
            }
        } else {
            $userBranchId = $user?->branch_id;
            if ($userBranchId !== null && (int) $transaction->branch_id !== (int) $userBranchId) {
                if (! in_array($user->role, ['super_admin', 'admin'], true)) {
                    abort(404, 'Transaction not found.');
                }
            }
        }
        $transaction->load(['cashier', 'items.product', 'items.productBatch']);
        return response()->json(['status' => true, 'data' => $transaction]);
    }
}

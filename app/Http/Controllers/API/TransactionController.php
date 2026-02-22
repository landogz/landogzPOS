<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
        if ($branchId !== null && $canChooseBranch) {
            $query->where('branch_id', $branchId);
        } elseif ($userBranchId !== null) {
            $query->where('branch_id', $userBranchId);
        }
        $transactions = $query->latest()->paginate($request->get('per_page', 15));
        return response()->json(['status' => true, 'data' => $transactions]);
    }

    public function show(Request $request, Transaction $transaction): JsonResponse
    {
        $user = $request->user();
        $userBranchId = $user?->branch_id;
        if ($userBranchId !== null && (int) $transaction->branch_id !== (int) $userBranchId) {
            if (! in_array($user->role, ['super_admin', 'admin'], true)) {
                abort(404, 'Transaction not found.');
            }
        }
        $transaction->load(['cashier', 'items.product', 'items.productBatch']);
        return response()->json(['status' => true, 'data' => $transaction]);
    }
}

<?php

namespace App\Http\Controllers\API\Inventory;

use App\Http\Controllers\Controller;
use App\Models\StockTransfer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockTransferController extends Controller
{
    use ScopesInventoryByBranch;

    public function store(Request $request): JsonResponse
    {
        $branchId = $this->branchIdForInventory($request);
        $validated = $request->validate([
            'from_branch_id' => 'required|exists:branches,id',
            'to_branch_id' => 'required|exists:branches,id',
            'product_id' => 'required|exists:products,id',
            'product_batch_id' => 'nullable|exists:product_batches,id',
            'quantity' => 'required|numeric|min:0.001',
        ]);
        if ((int) $validated['from_branch_id'] !== $branchId) {
            abort(403, 'You can only create transfers from your assigned branch.');
        }
        $validated['status'] = 'pending';
        $transfer = StockTransfer::create($validated);
        return response()->json(['status' => true, 'message' => 'Transfer created.', 'data' => $transfer], 201);
    }
}

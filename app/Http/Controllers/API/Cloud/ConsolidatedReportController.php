<?php

namespace App\Http\Controllers\API\Cloud;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsolidatedReportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to = $request->get('to', now()->toDateString());
        $rows = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('branch_id, date(created_at) as date, sum(total) as total, count(*) as count')
            ->groupBy('branch_id', 'date')
            ->get();
        $branchIds = $rows->pluck('branch_id')->unique()->filter()->values();
        $branches = \App\Models\Branch::whereIn('id', $branchIds)->get()->keyBy('id');
        $data = $rows->map(fn ($r) => [
            'branch_id' => $r->branch_id,
            'branch_name' => $branches->get($r->branch_id)?->name,
            'date' => $r->date,
            'total' => (float) $r->total,
            'count' => (int) $r->count,
        ]);
        return response()->json(['status' => true, 'data' => $data]);
    }
}

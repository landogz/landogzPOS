<?php

namespace App\Http\Controllers\API\Cloud;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ChainDashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $branchCount = Branch::count();
        $todaySales = Transaction::where('status', 'completed')->whereDate('created_at', today())->sum('total');
        $data = [
            'branches_count' => $branchCount,
            'today_sales' => (float) $todaySales,
        ];
        return response()->json(['status' => true, 'data' => $data]);
    }
}

<?php

namespace App\Http\Controllers\API\POS;

use App\Http\Controllers\Controller;
use App\Models\PosSession;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PosCashierController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $openSession = PosSession::where('cashier_id', $userId)->whereNull('closed_at')->first();
        $todaySales = Transaction::where('cashier_id', $userId)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('total');
        $todayCount = Transaction::where('cashier_id', $userId)
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();
        return response()->json([
            'status' => true,
            'data' => [
                'open_session' => $openSession,
                'today_sales' => (float) $todaySales,
                'today_transaction_count' => $todayCount,
            ],
        ]);
    }
}

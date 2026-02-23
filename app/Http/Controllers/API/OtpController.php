<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\SemaphoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function __construct(
        protected SemaphoreService $semaphore
    ) {}

    /**
     * Send OTP to the given mobile number (Philippine format).
     * POST /api/v1/otp/send
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'number' => 'required|string|min:10|max:15',
        ]);

        $number = $request->input('number');
        $result = $this->semaphore->sendOtp($number);

        if (!$result['success']) {
            return response()->json([
                'status' => false,
                'message' => $result['error'] ?? 'Failed to send OTP.',
                'errors' => ['number' => [$result['error'] ?? 'Failed to send OTP.']],
            ], 422);
        }

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully.',
            'data' => [
                'message_id' => $result['message_id'],
                'expires_in_seconds' => config('services.semaphore.otp_ttl_seconds', 300),
            ],
        ]);
    }

    /**
     * Verify OTP for the given mobile number.
     * POST /api/v1/otp/verify
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'number' => 'required|string|min:10|max:15',
            'code' => 'required|string|size:6|regex:/^\d{6}$/',
        ]);

        $number = $request->input('number');
        $code = $request->input('code');

        if (!$this->semaphore->verifyOtp($number, $code)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired OTP.',
                'errors' => ['code' => ['Invalid or expired OTP.']],
            ], 422);
        }

        return response()->json([
            'status' => true,
            'message' => 'OTP verified successfully.',
            'data' => ['verified' => true],
        ]);
    }
}

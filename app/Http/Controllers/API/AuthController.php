<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\LoginOtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login with email + password. Returns token, user, branch, permissions.
     * For super_admin when OTP is enabled: returns otp_required and sends OTP via email or SMS.
     * When no account found or invalid credentials: 401 with standard error envelope.
     */
    public function login(Request $request, LoginOtpService $loginOtp): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'The provided credentials are incorrect.',
                'errors' => ['email' => ['The provided credentials are incorrect.']],
            ], 401);
        }

        if (! $user->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'Account is inactive.',
                'errors' => ['email' => ['Account is inactive.']],
            ], 403);
        }

        $otpEnabled = config('otp.super_admin_login.enabled', false);
        $channel = config('otp.super_admin_login.channel', 'sms');

        if ($otpEnabled && $user->role === 'super_admin') {
            $result = $loginOtp->sendLoginOtp($user);
            if (! $result['success']) {
                return response()->json([
                    'status' => false,
                    'message' => $result['error'] ?? 'Failed to send verification code.',
                    'errors' => ['email' => [$result['error'] ?? 'Failed to send verification code.']],
                ], 422);
            }
            $sentTo = $channel === 'sms'
                ? 'your registered phone number'
                : $user->email;
            AuditLogService::log('login_otp_sent', 'users', (int) $user->id, null, ['channel' => $channel], $request, $user->branch_id, $user->id);
            return response()->json([
                'status' => 'success',
                'message' => 'Verification code sent.',
                'data' => [
                    'otp_required' => true,
                    'channel' => $channel,
                    'email' => $user->email,
                    'message' => 'A verification code has been sent to ' . $sentTo . '.',
                ],
            ]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('login')->plainTextToken;
        $user->load('branch.company');
        $permissions = $this->permissionsForRole($user->role);

        AuditLogService::log('login', 'users', (int) $user->id, null, ['login_at' => now()->toIso8601String()], $request, $user->branch_id, $user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => $user,
                'branch' => $user->branch,
                'permissions' => $permissions,
            ],
        ]);
    }

    /**
     * Verify OTP and complete super_admin login. POST with email + code.
     */
    public function verifyLoginOtp(Request $request, LoginOtpService $loginOtp): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6|regex:/^\d{6}$/',
        ]);

        $email = $request->input('email');
        $code = $request->input('code');

        if (! $loginOtp->verifyLoginOtp($email, $code)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired verification code.',
                'errors' => ['code' => ['Invalid or expired verification code.']],
            ], 422);
        }

        $user = User::where('email', $email)->first();
        if (! $user || ! $user->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'Account not found or inactive.',
                'errors' => ['email' => ['Account not found or inactive.']],
            ], 403);
        }

        $user->tokens()->delete();
        $token = $user->createToken('login')->plainTextToken;
        $user->load('branch.company');
        $permissions = $this->permissionsForRole($user->role);

        AuditLogService::log('login_otp_verified', 'users', (int) $user->id, null, ['login_at' => now()->toIso8601String()], $request, $user->branch_id, $user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => $user,
                'branch' => $user->branch,
                'permissions' => $permissions,
            ],
        ]);
    }

    /**
     * Resend login OTP for super_admin. POST with email only (throttled).
     */
    public function resendLoginOtp(Request $request, LoginOtpService $loginOtp): JsonResponse
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (! $user || $user->role !== 'super_admin' || ! $user->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'Account not found or not eligible for OTP.',
                'errors' => ['email' => ['Account not found or not eligible for OTP.']],
            ], 404);
        }
        $result = $loginOtp->sendLoginOtp($user);
        if (! $result['success']) {
            return response()->json([
                'status' => false,
                'message' => $result['error'] ?? 'Failed to resend verification code.',
                'errors' => ['email' => [$result['error'] ?? 'Failed to resend verification code.']],
            ], 422);
        }
        $channel = config('otp.super_admin_login.channel', 'email');
        AuditLogService::log('login_otp_sent', 'users', (int) $user->id, null, ['channel' => $channel, 'resend' => true], $request, $user->branch_id, $user->id);
        return response()->json([
            'status' => 'success',
            'message' => 'Verification code sent again.',
            'data' => ['email' => $user->email],
        ]);
    }

    /**
     * Quick login for cashier terminals (PIN only, branch + PIN).
     */
    public function loginPin(Request $request): JsonResponse
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'pin' => 'required|string|size:4',
        ]);

        $user = \App\Models\User::where('branch_id', $request->branch_id)
            ->where('is_active', true)
            ->get()
            ->first(fn ($u) => $u->pin_hash && Hash::check($request->pin, $u->pin_hash));

        if (!$user) {
            throw ValidationException::withMessages(['pin' => ['Invalid PIN or branch.']]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('pin-login')->plainTextToken;
        $user->load('branch.company');
        $permissions = $this->permissionsForRole($user->role);

        AuditLogService::log('login_pin', 'users', (int) $user->id, null, ['login_at' => now()->toIso8601String()], $request, $user->branch_id, $user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => $user,
                'branch' => $user->branch,
                'permissions' => $permissions,
            ],
        ]);
    }

    /**
     * Logout: revoke current token.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        AuditLogService::log('logout', 'users', $user?->id, null, ['logout_at' => now()->toIso8601String()], $request, $user?->branch_id, $user?->id);
        $user->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out.',
            'data' => null,
        ]);
    }

    /**
     * Current authenticated user + branch (alias of GET /user with envelope).
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('branch.company');
        $permissions = $this->permissionsForRole($user->role);
        return response()->json([
            'status' => 'success',
            'message' => null,
            'data' => [
                'user' => $user,
                'branch' => $user->branch,
                'permissions' => $permissions,
            ],
        ]);
    }

    /**
     * Permissions per role.
     * super_admin: all. admin: owner (company) – branches, BIR, create managers. manager: branch-scoped. pharmacist: inventory only. cashier: POS.
     */
    private function permissionsForRole(string $role): array
    {
        $map = [
            'super_admin' => ['*'],
            'admin' => ['dashboard', 'users', 'suppliers', 'products', 'categories', 'transactions', 'inventory', 'reports', 'branches', 'receipts'],
            'manager' => ['dashboard', 'users', 'suppliers', 'products', 'categories', 'transactions', 'inventory', 'reports', 'receipts'],
            'inventory_manager' => ['products', 'inventory', 'reports', 'receipts', 'stock-levels', 'batches'],
            'pharmacist' => ['inventory'],
            'cashier' => ['transactions', 'receipts', 'products.lookup'],
        ];
        return $map[$role] ?? ['transactions', 'receipts', 'products.lookup'];
    }
}

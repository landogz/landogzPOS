<?php

namespace App\Services;

use App\Mail\LoginOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LoginOtpService
{
    protected string $cachePrefix = 'login_otp:';
    protected int $ttl;
    protected int $length;

    public function __construct()
    {
        $config = config('otp.super_admin_login', []);
        $this->ttl = $config['ttl_seconds'] ?? 300;
        $this->length = (int) ($config['length'] ?? 6);
    }

    /**
     * Send OTP for super_admin login. Stores code keyed by email; sends via sms or email.
     * Channel is read from config at send time so .env changes take effect after config:clear.
     * Returns ['success' => bool, 'error' => ?string].
     */
    public function sendLoginOtp(User $user): array
    {
        $email = $user->email;
        $code = $this->generateCode();
        $this->store($email, $code);

        $channel = strtolower((string) (config('otp.super_admin_login.channel') ?? 'sms'));
        if ($channel === 'sms') {
            return $this->sendViaSms($user, $code);
        }

        return $this->sendViaEmail($email, $code);
    }

    /**
     * Verify login OTP for the given email. Returns true if valid and consumes the OTP.
     */
    public function verifyLoginOtp(string $email, string $code): bool
    {
        $key = $this->cacheKey($email);
        $stored = Cache::get($key);
        if ($stored === null || ! is_string($stored)) {
            return false;
        }
        if (! hash_equals($stored, $code)) {
            return false;
        }
        Cache::forget($key);
        return true;
    }

    protected function sendViaSms(User $user, string $code): array
    {
        $phone = $user->phone_number;
        if (empty($phone)) {
            Log::warning('Login OTP: SMS requested but user has no phone_number', ['user_id' => $user->id]);
            return [
                'success' => false,
                'error' => 'Phone number is required for SMS verification. Please contact your administrator to add your phone number.',
            ];
        }

        $semaphore = app(SemaphoreService::class);
        $result = $semaphore->sendOtp($phone, null, $code);
        if (! $result['success']) {
            return [
                'success' => false,
                'error' => $result['error'] ?? 'Failed to send SMS.',
            ];
        }

        // Semaphore stores OTP by phone; we need verify by email. So we keep our own store by email (already done above).
        return ['success' => true, 'error' => null];
    }

    protected function sendViaEmail(string $email, string $code): array
    {
        try {
            $expiresInMinutes = (int) ceil($this->ttl / 60);
            Mail::to($email)->send(new LoginOtpMail($code, $expiresInMinutes));
            return ['success' => true, 'error' => null];
        } catch (\Throwable $e) {
            Log::warning('Login OTP: email send failed', ['email' => $email, 'message' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Failed to send verification email. Please try again later.',
            ];
        }
    }

    protected function store(string $email, string $code): void
    {
        Cache::put($this->cacheKey($email), $code, $this->ttl);
    }

    protected function cacheKey(string $email): string
    {
        return $this->cachePrefix . strtolower($email);
    }

    protected function generateCode(): string
    {
        $min = (int) str_pad('1', $this->length, '0');
        $max = (int) str_repeat('9', $this->length);
        return (string) random_int($min, $max);
    }

    public function getChannel(): string
    {
        return strtolower((string) (config('otp.super_admin_login.channel') ?? 'sms'));
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SemaphoreService
{
    protected string $baseUrl = 'https://api.semaphore.co/api/v4';

    protected ?string $apiKey = null;
    protected ?string $senderName = null;
    protected int $otpTtl = 300;
    protected int $otpLength = 6;
    protected string $otpMessage = '';

    public function __construct(
        ?string $apiKey = null,
        ?string $senderName = null
    ) {
        $this->apiKey = $apiKey ?? config('services.semaphore.api_key');
        $this->senderName = $senderName ?? config('services.semaphore.sender_name', 'SEMAPHORE');
        $this->otpTtl = (int) config('services.semaphore.otp_ttl_seconds', 300);
        $this->otpLength = (int) config('services.semaphore.otp_length', 6);
        $this->otpMessage = config('services.semaphore.otp_message', 'Your Landogz POS verification code is {otp}. Valid for 5 minutes.');
    }

    /**
     * Normalize Philippine mobile number to 63XXXXXXXXX (no +).
     */
    public function normalizeNumber(string $number): string
    {
        $number = preg_replace('/\D/', '', $number);
        if (str_starts_with($number, '0')) {
            $number = '63' . substr($number, 1);
        } elseif (!str_starts_with($number, '63')) {
            $number = '63' . $number;
        }
        return $number;
    }

    /**
     * Send a raw SMS via Semaphore (standard messages endpoint).
     */
    public function sendSms(string $number, string $message, ?string $senderName = null): array
    {
        if (empty($this->apiKey)) {
            Log::warning('Semaphore: SEMAPHORE_API_KEY not set');
            return ['success' => false, 'message_id' => null, 'error' => 'SMS provider not configured.'];
        }
        $number = $this->normalizeNumber($number);
        $senderName = $senderName ?? $this->senderName;

        $response = Http::asForm()->post("{$this->baseUrl}/messages", [
            'apikey' => $this->apiKey,
            'number' => $number,
            'message' => $message,
            'sendername' => $senderName,
        ]);

        return $this->parseResponse($response, $number, 'messages');
    }

    /**
     * Generate OTP, store in cache, and send via Semaphore OTP endpoint.
     * Uses https://api.semaphore.co/api/v4/otp (dedicated OTP route, not rate limited, 2 credits per 160 chars).
     * Message can use {otp} placeholder; we pass "code" so Semaphore inserts our OTP.
     * When $useCode is provided (e.g. from LoginOtpService), that code is sent and we do not store in cache (caller verifies elsewhere).
     * Returns ['success' => bool, 'message_id' => ?int, 'code' => string, 'error' => ?string].
     */
    public function sendOtp(string $number, ?string $messageTemplate = null, ?string $useCode = null): array
    {
        if (empty($this->apiKey)) {
            Log::warning('Semaphore: SEMAPHORE_API_KEY not set');
            return ['success' => false, 'message_id' => null, 'code' => '', 'error' => 'SMS provider not configured.'];
        }

        $number = $this->normalizeNumber($number);
        $code = $useCode !== null && $useCode !== '' ? $useCode : $this->generateOtp();
        $message = $messageTemplate ?? $this->otpMessage;

        $response = Http::asForm()->post("{$this->baseUrl}/otp", [
            'apikey' => $this->apiKey,
            'number' => $number,
            'message' => $message,
            'sendername' => $this->senderName,
            'code' => $code,
        ]);

        $result = $this->parseResponse($response, $number, 'otp');
        if ($result['success'] && $useCode === null) {
            $this->storeOtp($number, $code);
        }
        $result['code'] = $code;
        return $result;
    }

    /**
     * Verify OTP for the given number. Returns true if valid and consumes the OTP.
     */
    public function verifyOtp(string $number, string $code): bool
    {
        $number = $this->normalizeNumber($number);
        $key = $this->cacheKey($number);
        $stored = Cache::get($key);
        if ($stored === null || !is_string($stored)) {
            return false;
        }
        if (!hash_equals($stored, $code)) {
            return false;
        }
        Cache::forget($key);
        return true;
    }

    /**
     * Check if OTP is still valid for the number (without consuming it).
     */
    public function hasPendingOtp(string $number): bool
    {
        $number = $this->normalizeNumber($number);
        return Cache::has($this->cacheKey($number));
    }

    protected function cacheKey(string $number): string
    {
        return 'semaphore_otp:' . $number;
    }

    protected function storeOtp(string $number, string $code): void
    {
        Cache::put($this->cacheKey($number), $code, $this->otpTtl);
    }

    protected function generateOtp(): string
    {
        $min = (int) str_pad('1', $this->otpLength, '0');
        $max = (int) str_repeat('9', $this->otpLength);
        return (string) random_int($min, $max);
    }

    /**
     * @return array{success: bool, message_id: ?int, error: ?string}
     */
    protected function parseResponse($response, string $number, string $context): array
    {
        if (!$response->successful()) {
            $body = $response->body();
            Log::warning("Semaphore {$context} failed", [
                'status' => $response->status(),
                'body' => $body,
                'number' => $number,
            ]);
            return [
                'success' => false,
                'message_id' => null,
                'error' => $response->status() === 422 ? 'Invalid number or message.' : 'Failed to send SMS.',
            ];
        }

        $data = $response->json();
        if (is_array($data) && isset($data[0])) {
            $first = $data[0];
            $status = $first['status'] ?? '';
            $success = in_array(strtolower($status), ['queued', 'pending', 'sent'], true);
            return [
                'success' => $success,
                'message_id' => $first['message_id'] ?? null,
                'error' => $success ? null : ($status ? "Status: {$status}" : 'Unknown error'),
            ];
        }

        return [
            'success' => true,
            'message_id' => $data['message_id'] ?? null,
            'error' => null,
        ];
    }
}

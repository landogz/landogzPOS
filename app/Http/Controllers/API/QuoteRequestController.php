<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class QuoteRequestController extends Controller
{
    /**
     * Validate quote form and send email to configured address.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email',
            'business_type' => 'nullable|string|max:100',
            'branches' => 'nullable|string|max:20',
            'region' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'existing_pos' => 'nullable|string|in:Yes,No',
            'requirement' => 'nullable|string|max:2000',
        ]);

        $to = env('QUOTE_REQUEST_EMAIL', 'rolan.benavidez@gmail.com');
        if (empty($to)) {
            Log::warning('Quote request: QUOTE_REQUEST_EMAIL not set');
            return response()->json([
                'status' => false,
                'message' => 'Quote request is not configured. Please try again later.',
            ], 500);
        }

        try {
            $subject = 'Landogz POS – Request a Quote: ' . $validated['fullname'];
            $body = $this->buildEmailBody($validated);

            Mail::raw($body, function ($message) use ($to, $subject, $validated) {
                $message->to($to)
                    ->subject($subject)
                    ->replyTo($validated['email'], $validated['fullname']);
            });

            return response()->json([
                'status' => true,
                'message' => 'Thank you. Your quote request has been sent. We will get back to you soon.',
                'data' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('Quote request email failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status' => false,
                'message' => 'Unable to send your request. Please try again or contact us directly.',
                'errors' => [],
            ], 500);
        }
    }

    protected function buildEmailBody(array $data): string
    {
        $lines = [
            'Landogz POS – Quote Request',
            str_repeat('—', 40),
            'Full Name: ' . ($data['fullname'] ?? ''),
            'Company: ' . ($data['company'] ?? '—'),
            'Phone: ' . ($data['phone'] ?? ''),
            'Email: ' . ($data['email'] ?? ''),
            'Type of Business: ' . ($data['business_type'] ?? '—'),
            'Store/Branches: ' . ($data['branches'] ?? '—'),
            'Region: ' . ($data['region'] ?? '—'),
            'City/Province: ' . ($data['city'] ?? '—'),
            'Existing POS: ' . ($data['existing_pos'] ?? '—'),
            '',
            'Requirements / Notes:',
            $data['requirement'] ?? '—',
            '',
            'Submitted at: ' . now()->toDateTimeString(),
        ];
        return implode("\n", $lines);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BirSetting;
use App\Services\AuditLogService;
use App\Services\SyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BirSettingsController extends Controller
{
    /**
     * Get system provider BIR settings. One config for ALL receipts (BIR-required footer).
     * Returns the first BirSetting record; branch_id is only for DB constraint.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || $user->role !== 'super_admin') {
            return response()->json(['status' => false, 'message' => 'Only Super Admin can access BIR settings.'], 403);
        }
        $settings = BirSetting::query()->with('branch:id,name')->orderBy('id')->first();
        if (! $settings) {
            $firstBranch = \App\Models\Branch::orderBy('id')->first();
            if ($firstBranch) {
                $settings = BirSetting::create(['branch_id' => $firstBranch->id]);
                $settings->load('branch');
            }
        }
        return response()->json([
            'status' => 'success',
            'data' => $settings,
        ]);
    }

    /**
     * Update system provider BIR settings. One config for ALL receipts (applies to every branch).
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || $user->role !== 'super_admin') {
            return response()->json(['status' => false, 'message' => 'Only Super Admin can update BIR settings.'], 403);
        }
        $validated = $request->validate([
            'provider_name' => 'nullable|string|max:255',
            'provider_address' => 'nullable|string|max:500',
            'tin' => 'nullable|string|max:50',
            'accreditation_number' => 'nullable|string|max:100',
            'series_start' => 'nullable|string|max:50',
            'series_end' => 'nullable|string|max:50',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
            'ptu_number' => 'nullable|string|max:100',
            'validity_statement' => 'nullable|string',
            'footer_text' => 'nullable|string',
        ]);
        $firstBranch = \App\Models\Branch::orderBy('id')->first();
        if (! $firstBranch) {
            return response()->json(['status' => false, 'message' => 'Create at least one branch first.'], 422);
        }
        $settings = BirSetting::orderBy('id')->first();
        if (! $settings) {
            $settings = BirSetting::create(['branch_id' => $firstBranch->id]);
        }
        $keys = ['provider_name', 'provider_address', 'tin', 'accreditation_number', 'series_start', 'series_end', 'valid_from', 'valid_until', 'ptu_number', 'validity_statement', 'footer_text'];
        $old = $settings->only($keys);
        $settings->update($validated);
        $settings->load('branch');
        AuditLogService::log('bir_settings_updated', 'bir_settings', (int) $settings->id, $old, $settings->only($keys), $request, $settings->branch_id, $user?->id);
        app(SyncService::class)->enqueueBirSetting($settings);
        return response()->json([
            'status' => 'success',
            'message' => 'BIR settings updated. This system provider footer applies to all receipts.',
            'data' => $settings,
        ]);
    }
}

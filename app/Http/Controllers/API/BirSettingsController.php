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
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || $user->role !== 'super_admin') {
            return response()->json(['status' => false, 'message' => 'Only Super Admin can access BIR settings.'], 403);
        }
        $branchId = $user->branch_id ?? $request->get('branch_id');
        $query = BirSetting::query()->with('branch:id,name');
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
        $settings = $query->first();
        if (! $settings && $branchId) {
            $settings = BirSetting::create(['branch_id' => $branchId]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $settings,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || $user->role !== 'super_admin') {
            return response()->json(['status' => false, 'message' => 'Only Super Admin can update BIR settings.'], 403);
        }
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
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
        $settings = BirSetting::firstOrCreate(
            ['branch_id' => $validated['branch_id']],
            ['branch_id' => $validated['branch_id']]
        );
        $keys = ['provider_name', 'provider_address', 'tin', 'accreditation_number', 'series_start', 'series_end', 'valid_from', 'valid_until', 'ptu_number', 'validity_statement', 'footer_text'];
        $old = $settings->only($keys);
        $settings->update($validated);
        $settings->load('branch');
        AuditLogService::log('bir_settings_updated', 'bir_settings', (int) $settings->id, $old, $settings->only($keys), $request, $settings->branch_id, $user?->id);
        app(SyncService::class)->enqueueBirSetting($settings);
        return response()->json([
            'status' => 'success',
            'message' => 'BIR settings updated.',
            'data' => $settings,
        ]);
    }
}

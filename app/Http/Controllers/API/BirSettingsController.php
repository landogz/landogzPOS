<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BirSetting;
use App\Models\Branch;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BirSettingsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $branchId = $user?->branch_id ?? $request->get('branch_id');
        if (! $branchId && $user && $user->role === 'admin' && $user->company_id) {
            $firstBranch = Branch::where('company_id', $user->company_id)->orderBy('id')->first();
            $branchId = $firstBranch?->id;
        }
        $query = BirSetting::query()->with('branch:id,name');
        if ($branchId) {
            $query->where('branch_id', $branchId);
            if ($user && $user->role === 'admin' && $user->company_id) {
                $branch = Branch::find($branchId);
                if (! $branch || (int) $branch->company_id !== (int) $user->company_id) {
                    $query->whereRaw('1 = 0');
                }
            }
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
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'tin' => 'nullable|string|max:50',
            'accreditation_number' => 'nullable|string|max:100',
            'series_start' => 'nullable|string|max:50',
            'series_end' => 'nullable|string|max:50',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
            'footer_text' => 'nullable|string',
        ]);
        $user = $request->user();
        if ($user && $user->role === 'admin' && $user->company_id) {
            $branch = Branch::find($validated['branch_id']);
            if (! $branch || (int) $branch->company_id !== (int) $user->company_id) {
                return response()->json(['status' => false, 'message' => 'You can only update BIR settings for branches of your company.'], 403);
            }
        }
        $settings = BirSetting::firstOrCreate(
            ['branch_id' => $validated['branch_id']],
            ['branch_id' => $validated['branch_id']]
        );
        $old = $settings->only(['tin', 'accreditation_number', 'series_start', 'series_end', 'valid_from', 'valid_until']);
        $settings->update($validated);
        $settings->load('branch');
        AuditLogService::log('bir_settings_updated', 'bir_settings', (int) $settings->id, $old, $settings->only(['tin', 'accreditation_number', 'series_start', 'series_end', 'valid_from', 'valid_until']), $request, $settings->branch_id, $user?->id);
        return response()->json([
            'status' => 'success',
            'message' => 'BIR settings updated.',
            'data' => $settings,
        ]);
    }
}

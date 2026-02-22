<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BirSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BirSettingsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $branchId = $request->user()?->branch_id ?? $request->get('branch_id');
        $query = BirSetting::query()->with('branch:id,name');
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }
        $settings = $query->first(); // one per branch
        if (!$settings && $branchId) {
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
        $settings = BirSetting::firstOrCreate(
            ['branch_id' => $validated['branch_id']],
            ['branch_id' => $validated['branch_id']]
        );
        $settings->update($validated);
        $settings->load('branch');
        return response()->json([
            'status' => 'success',
            'message' => 'BIR settings updated.',
            'data' => $settings,
        ]);
    }
}

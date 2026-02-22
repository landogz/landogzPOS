<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(
        private CompanyService $service
    ) {}

    /**
     * List companies. Only super_admin can access.
     */
    public function index(Request $request): JsonResponse
    {
        if ($request->user()?->role !== 'super_admin') {
            return response()->json([
                'status' => false,
                'message' => 'Only Super Admin can manage companies.',
            ], 403);
        }
        $search = $request->query('search');
        $companies = $this->service->list($search);
        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $companies,
        ]);
    }

    public function show(Request $request, Company $company): JsonResponse
    {
        if ($request->user()?->role !== 'super_admin') {
            return response()->json([
                'status' => false,
                'message' => 'Only Super Admin can view companies.',
            ], 403);
        }
        $company->loadCount('branches');
        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $company,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        if ($request->user()?->role !== 'super_admin') {
            return response()->json([
                'status' => false,
                'message' => 'Only Super Admin can create companies.',
            ], 403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tin' => 'nullable|string|max:50',
            'bir_accreditation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'contact' => 'nullable|string|max:100',
        ]);
        $company = $this->service->create($validated);
        return response()->json([
            'status' => true,
            'message' => 'Company created.',
            'data' => $company,
        ], 201);
    }

    public function update(Request $request, Company $company): JsonResponse
    {
        if ($request->user()?->role !== 'super_admin') {
            return response()->json([
                'status' => false,
                'message' => 'Only Super Admin can update companies.',
            ], 403);
        }
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'tin' => 'nullable|string|max:50',
            'bir_accreditation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'contact' => 'nullable|string|max:100',
        ]);
        $company = $this->service->update($company, $validated);
        return response()->json([
            'status' => true,
            'message' => 'Company updated.',
            'data' => $company,
        ]);
    }

    public function destroy(Request $request, Company $company): JsonResponse
    {
        if ($request->user()?->role !== 'super_admin') {
            return response()->json([
                'status' => false,
                'message' => 'Only Super Admin can delete companies.',
            ], 403);
        }
        $this->service->delete($company);
        return response()->json([
            'status' => true,
            'message' => 'Company deleted.',
            'data' => null,
        ]);
    }
}

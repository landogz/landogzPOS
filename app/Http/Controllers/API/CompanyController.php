<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\AuditLogService;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(
        private CompanyService $service
    ) {}

    /**
     * List companies. super_admin: all; admin: only their company.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Unauthenticated.'], 401);
        }
        if ($user->role === 'super_admin') {
            $search = $request->query('search');
            $status = $request->query('status');
            $companies = $this->service->list($search, $status);
            return response()->json(['status' => true, 'message' => 'OK', 'data' => $companies]);
        }
        if ($user->role === 'admin' && $user->company_id) {
            $company = $this->service->get((int) $user->company_id);
            if (!$company) {
                return response()->json(['status' => true, 'message' => 'OK', 'data' => []]);
            }
            $company->loadCount('branches');
            return response()->json(['status' => true, 'message' => 'OK', 'data' => [$company]]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Only Super Admin can manage companies, or Admin can view their company.',
        ], 403);
    }

    public function show(Request $request, Company $company): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Unauthenticated.'], 401);
        }
        if ($user->role === 'super_admin') {
            $company->loadCount('branches');
            return response()->json(['status' => true, 'message' => 'OK', 'data' => $company]);
        }
        if ($user->role === 'admin' && $user->company_id && (int) $company->id === (int) $user->company_id) {
            $company->loadCount('branches');
            return response()->json(['status' => true, 'message' => 'OK', 'data' => $company]);
        }
        return response()->json(['status' => false, 'message' => 'You can only view your company.'], 403);
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
            'name'              => 'required|string|max:255',
            'tin'               => 'nullable|string|max:50',
            'bir_accreditation' => 'nullable|string|max:255',
            'address'           => 'nullable|string',
            'contact'           => 'nullable|string|max:100',
            'logo'              => 'nullable|image|max:2048',
            'is_vat'            => 'nullable|boolean',
            'admin_name'        => 'nullable|string|max:255',
            'admin_email'       => 'nullable|email|unique:users,email',
            'admin_password'    => 'nullable|string|min:8|confirmed',
        ]);
        $admin = null;
        if (!empty($validated['admin_email']) && !empty($validated['admin_password'])) {
            $admin = [
                'admin_name'     => $validated['admin_name'] ?? null,
                'admin_email'    => $validated['admin_email'],
                'admin_password' => $validated['admin_password'],
            ];
        }
        unset($validated['admin_name'], $validated['admin_email'], $validated['admin_password'], $validated['admin_password_confirmation']);
        $company = $this->service->create($validated, $request->file('logo'), $admin);
        AuditLogService::log('created', 'companies', (int) $company->id, null, $company->only(['name', 'tin', 'is_vat', 'is_active']), $request);
        return response()->json([
            'status' => true,
            'message' => 'Company created.' . ($admin ? ' Admin account created.' : ''),
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
            'name'              => 'sometimes|string|max:255',
            'tin'               => 'nullable|string|max:50',
            'bir_accreditation' => 'nullable|string|max:255',
            'address'           => 'nullable|string',
            'contact'           => 'nullable|string|max:100',
            'logo'              => 'nullable|image|max:2048',
            'is_vat'            => 'nullable|boolean',
        ]);
        $old = $company->only(['name', 'tin', 'bir_accreditation', 'is_vat', 'is_active']);
        $company = $this->service->update($company, $validated, $request->file('logo'));
        AuditLogService::log('updated', 'companies', (int) $company->id, $old, $company->only(['name', 'tin', 'bir_accreditation', 'is_vat', 'is_active']), $request);
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
        $old = $company->only(['id', 'name', 'tin', 'is_active']);
        $this->service->delete($company);
        AuditLogService::log('deleted', 'companies', (int) $old['id'], $old, null, $request);
        return response()->json([
            'status' => true,
            'message' => 'Company deleted.',
            'data' => null,
        ]);
    }

    public function toggleStatus(Request $request, Company $company): JsonResponse
    {
        if ($request->user()?->role !== 'super_admin') {
            return response()->json([
                'status' => false,
                'message' => 'Only Super Admin can manage companies.',
            ], 403);
        }
        $company = $this->service->toggleStatus($company);
        AuditLogService::log('status_toggled', 'companies', (int) $company->id, ['is_active' => ! $company->is_active], ['is_active' => $company->is_active], $request);
        return response()->json([
            'status' => true,
            'message' => $company->is_active ? 'Company enabled.' : 'Company disabled.',
            'data' => $company,
        ]);
    }

    public function summary(Request $request, Company $company): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Unauthenticated.'], 401);
        }
        if ($user->role === 'super_admin') {
            // continue
        } elseif ($user->role === 'admin' && $user->company_id && (int) $company->id === (int) $user->company_id) {
            // admin can view their company summary
        } else {
            return response()->json(['status' => false, 'message' => 'You can only view your company summary.'], 403);
        }
        $branchId = $request->query('branch_id');
        $branchId = $branchId !== null && $branchId !== '' ? (int) $branchId : null;
        $data = $this->service->getSummary(
            $company,
            $request->query('date_from'),
            $request->query('date_to'),
            $branchId > 0 ? $branchId : null
        );
        return response()->json([
            'status' => true,
            'message' => 'OK',
            'data' => $data,
        ]);
    }
}

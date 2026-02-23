<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogService
{
    /**
     * Log an action to audit_logs. Pass Request to auto-fill user_id, branch_id (from user), ip_address.
     */
    public static function log(
        string $action,
        ?string $tableName = null,
        ?int $recordId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Request $request = null,
        ?int $branchId = null,
        ?int $userId = null
    ): void {
        if ($request) {
            $user = $request->user();
            if ($userId === null && $user) {
                $userId = $user->id;
            }
            if ($branchId === null && $user && $user->branch_id) {
                $branchId = $user->branch_id;
            }
            if ($branchId === null && $user && $user->company_id && $recordId && $tableName === 'branches') {
                // branch_id can be inferred from branch record if needed
            }
        }

        AuditLog::create([
            'branch_id' => $branchId,
            'user_id' => $userId,
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $request?->ip(),
        ]);
    }
}

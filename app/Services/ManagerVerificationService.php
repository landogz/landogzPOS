<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ManagerVerificationService
{
    /**
     * Verify that the given PIN or password matches a manager for the branch.
     * Returns the manager User so the caller can use their name (e.g. for X-Reading Administrator).
     * Managers are users with role=manager, same branch_id, and is_active=true.
     *
     * @throws ValidationException when no manager matches
     */
    public function verifyForBranch(int $branchId, string $pinOrPassword): User
    {
        $managers = User::where('branch_id', $branchId)
            ->where('role', 'manager')
            ->where('is_active', true)
            ->get();

        $value = $pinOrPassword;

        foreach ($managers as $manager) {
            if ($manager->pin_hash && Hash::check($value, $manager->pin_hash)) {
                return $manager;
            }
            if ($manager->password && Hash::check($value, $manager->getAuthPassword())) {
                return $manager;
            }
        }

        throw ValidationException::withMessages([
            'pin_or_password' => ['Invalid manager PIN or password for this branch.'],
        ]);
    }
}

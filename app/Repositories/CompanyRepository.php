<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CompanyRepository
{
    public function getAll(?string $search = null): Collection
    {
        $query = Company::query()->orderBy('name');
        if ($search !== null && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('tin', 'like', '%' . $search . '%')
                    ->orWhere('contact', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%');
            });
        }
        return $query->get();
    }

    public function find(int $id): ?Company
    {
        return Company::find($id);
    }

    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function update(Company $company, array $data): Company
    {
        $company->update($data);
        return $company->fresh();
    }

    public function delete(Company $company): bool
    {
        return $company->delete();
    }

    public function toggleActive(Company $company): Company
    {
        $company->update(['is_active' => !$company->is_active]);
        return $company->fresh();
    }
}

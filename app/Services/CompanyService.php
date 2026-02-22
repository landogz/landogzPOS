<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Support\Collection;

class CompanyService
{
    public function __construct(
        private CompanyRepository $repository
    ) {}

    public function list(?string $search = null): Collection
    {
        return $this->repository->getAll($search);
    }

    public function get(int $id): ?Company
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Company
    {
        return $this->repository->create($data);
    }

    public function update(Company $company, array $data): Company
    {
        return $this->repository->update($company, $data);
    }

    public function delete(Company $company): bool
    {
        return $this->repository->delete($company);
    }
}

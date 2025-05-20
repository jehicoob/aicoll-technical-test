<?php

namespace App\Repositories\Company;

use App\Enums\CompanyStatus;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

final class CompanyRepository implements CompanyRepositoryInterface
{
    /**
     * Get all companies.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection
    {
        return Company::all();
    }

    /**
     * Find a company by nit.
     *
     * @param string $nit
     * @return \App\Models\Company|null
     */
    public function findByNit(string $nit): ?Company
    {
        return Company::where('nit', $nit)->first();
    }

    /**
     * Create a new company.
     *
     * @param array $data
     * @return \App\Models\Company
     */
    public function create(array $data): Company
    {
        $data['status'] ??= CompanyStatus::ACTIVE; // status "active" is default
        return Company::create($data);
    }

    /**
     * Update a company by nit.
     *
     * @param \App\Models\Company $company
     * @param array $data
     * @return \App\Models\Company|null
     */
    public function update(Company $company, array $data): ?Company
    {
        $company->update($data);
        return $company;
    }

    /**
     * Delete a company by nit.
     *
     * @param \App\Models\Company $company
     * @return bool
     */
    public function delete(Company $company): bool
    {
        return $company->delete();
    }

    /**
     * Delete inactive companies.
     *
     * @return int
     */
    public function deleteInactive(): int
    {
        return Company::where('status', CompanyStatus::INACTIVE)->delete();
    }
}
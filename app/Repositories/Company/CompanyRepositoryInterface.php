<?php

namespace App\Repositories\Company;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Company;

interface CompanyRepositoryInterface
{
    /**
     * Get all companies.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection;

    /**
     * Find a company by nit.
     *
     * @param string $nit
     * @return \App\Models\Company|null
     */
    public function findByNit(string $nit): ?Company;

    /**
     * Create a new company.
     *
     * @param array $data
     * @return \App\Models\Company
     */
    public function create(array $data): Company;

    /**
     * Update a company by nit.
     *
     * @param Company $company
     * @param array $data
     * @return \App\Models\Company|null
     */
    public function update(Company $company, array $data): ?Company;

    /**
     * Delete a company by nit.
     *
     * @param Company $company
     * @return bool
     */
    public function delete(Company $company): bool;

    /**
     * Delete inactive companies.
     *
     * @return int
     */
    public function deleteInactive(): int;
}


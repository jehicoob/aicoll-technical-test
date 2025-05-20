<?php

namespace App\Services;

use App\Enums\CompanyStatus;
use App\Exceptions\Domain\Company\CompanyActiveException;
use App\Exceptions\Domain\Company\CompanyNotFoundException;
use App\Exceptions\Domain\Company\DuplicateCompanyException;
use App\Models\Company;
use App\Repositories\Company\CompanyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class CompanyService
{
    /**
     * @var CompanyRepositoryInterface
     */
    protected $companyRepository;

    /**
     * Constructor
     *
     * @param CompanyRepositoryInterface $companyRepository
     */
    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * Get all companies.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCompanies(): Collection
    {
        return $this->companyRepository->getAll();
    }

    /**
     * Get company by NIT.
     *
     * @param string $nit
     * @return \App\Models\Company|null
     * @throws \App\Exceptions\Domain\Company\CompanyNotFoundException
     */
    public function getCompanyByNit(string $nit): Company
    {
        $company = $this->companyRepository->findByNit($nit);

        if (!$company) {
            throw new CompanyNotFoundException($nit);
        }

        return $company;
    }

    /**
     * Create a new company.
     *
     * @param array $data
     * @return \App\Models\Company
     * @throws \App\Exceptions\Domain\Company\DuplicateCompanyException
     */
    public function createCompany(array $data): Company
    {
        $company = $this->getCompanyByNit($data['nit']);

        if ($company) {
            throw new DuplicateCompanyException($data['nit']);
        }

        return $this->companyRepository->create($data);
    }

    /**
     * Update a company by NIT.
     *
     * @param string $nit
     * @param array $data
     * @return \App\Models\Company|null
     */
    public function updateCompany(string $nit, array $data): ?Company
    {
        $company = $this->getCompanyByNit($nit);
        return $this->companyRepository->update($company, $data);
    }

    /**
     * Delete a company by nit.
     *
     * @param string $nit
     * @return bool
     * @throws \App\Exceptions\Domain\Company\CompanyActiveException
     */
    public function deleteCompany(string $nit): bool
    {
        $company = $this->getCompanyByNit($nit);

        if ($company->status === CompanyStatus::ACTIVE) {
            throw new CompanyActiveException($nit);
        }

        return $this->companyRepository->delete($company);
    }

    /**
     * Delete inactive companies.
     *
     * @return int Number of companies deleted
     */
    public function deleteInactiveCompanies(): int
    {
        return $this->companyRepository->deleteInactive();
    }
}
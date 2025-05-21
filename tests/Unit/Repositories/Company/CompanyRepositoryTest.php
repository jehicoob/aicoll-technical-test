<?php

namespace Tests\Unit\Repositories\Company;

use App\Enums\CompanyStatus;
use App\Models\Company;
use App\Repositories\Company\CompanyRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private CompanyRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CompanyRepository();
    }

    public function test_get_all_companies()
    {
        // Arrange
        Company::factory()->count(3)->create();

        // Act
        $companies = $this->repository->getAll();

        // Assert
        $this->assertCount(3, $companies);
    }

    public function test_find_company_by_nit()
    {
        // Arrange
        $company = Company::factory()->create(['nit' => '123456789']);

        // Act
        $foundCompany = $this->repository->findByNit('123456789');

        // Assert
        $this->assertNotNull($foundCompany);
        $this->assertEquals($company->id, $foundCompany->id);
    }

    public function test_find_company_by_nit_returns_null_when_not_found()
    {
        // Act
        $foundCompany = $this->repository->findByNit('non-existent-nit');

        // Assert
        $this->assertNull($foundCompany);
    }

    public function test_create_company()
    {
        // Arrange
        $data = [
            'nit' => '123456789',
            'name' => 'Test Company',
            'address' => '123 Test Street',
            'phone' => '1234567890',
        ];

        // Act
        $company = $this->repository->create($data);

        // Assert
        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals('123456789', $company->nit);
        $this->assertEquals('Test Company', $company->name);
        $this->assertEquals(CompanyStatus::ACTIVE, $company->status);
    }

    public function test_update_company()
    {
        // Arrange
        $company = Company::factory()->create(['nit' => '123456789']);
        $data = [
            'name' => 'Updated Company',
            'address' => 'Updated Address',
        ];

        // Act
        $updatedCompany = $this->repository->update($company, $data);

        // Assert
        $this->assertEquals('Updated Company', $updatedCompany->name);
        $this->assertEquals('Updated Address', $updatedCompany->address);
        $this->assertEquals('123456789', $updatedCompany->nit); // Nit shouldn't change
    }

    public function test_delete_company()
    {
        // Arrange
        $company = Company::factory()->create();

        // Act
        $result = $this->repository->delete($company);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    public function test_delete_inactive_companies()
    {
        // Arrange
        Company::factory()->count(3)->create(['status' => CompanyStatus::ACTIVE]);
        Company::factory()->count(2)->create(['status' => CompanyStatus::INACTIVE]);

        // Act
        $deletedCount = $this->repository->deleteInactive();

        // Assert
        $this->assertEquals(2, $deletedCount);
        $this->assertCount(3, Company::all());
        $this->assertDatabaseCount('companies', 3);
    }
}
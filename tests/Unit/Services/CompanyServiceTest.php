<?php

namespace Tests\Unit\Services;

use App\Enums\CompanyStatus;
use App\Exceptions\Domain\Company\CompanyActiveException;
use App\Exceptions\Domain\Company\CompanyNotFoundException;
use App\Exceptions\Domain\Company\DuplicateCompanyException;
use App\Models\Company;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Services\CompanyService;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use PHPUnit\Framework\TestCase;

class CompanyServiceTest extends TestCase
{
    private CompanyRepositoryInterface $mockRepository;

    private CompanyService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockRepository = Mockery::mock(CompanyRepositoryInterface::class);
        $this->service = new CompanyService($this->mockRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_companies()
    {
        // Arrange
        $companies = new Collection([
            new Company(['nit' => '123', 'name' => 'Company 1']),
            new Company(['nit' => '456', 'name' => 'Company 2']),
        ]);

        // dd($companies);

        $this->mockRepository->shouldReceive('getAll')
            ->once()
            ->andReturn($companies);

        // Act
        $result = $this->service->getAllCompanies();

        // Assert
        $this->assertCount(2, $result);
        $this->assertEquals('Company 1', $result[0]->name);
    }

    public function test_get_company_by_nit()
    {
        // Arrange
        $company = new Company(['nit' => '123456', 'name' => 'Company 1']);

        $this->mockRepository->shouldReceive('findByNit')
            ->once()
            ->andReturn($company);

        // Act
        $result = $this->service->getCompanyByNit('123456');

        // Assert
        $this->assertEquals('123456', $result->nit);
    }

    public function test_get_company_by_nit_throws_exception_when_not_found()
    {
        // Arrange
        $this->mockRepository->shouldReceive('findByNit')
            ->with('non-existent')
            ->once()
            ->andReturnNull();

        // Assert & Act
        $this->expectException(CompanyNotFoundException::class);
        $this->service->getCompanyByNit('non-existent');
    }

    public function test_create_company()
    {
        // Arrange
        $data = [
            'nit' => '123',
            'name' => 'New Company',
            'address' => 'Address',
            'phone' => '123456',
        ];

        $company = new Company($data);

        $this->mockRepository->shouldReceive('findByNit')
            ->with('123')
            ->once()
            ->andReturnNull();

        $this->mockRepository->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn($company);

        // Act
        $result = $this->service->createCompany($data);

        // Assert
        $this->assertEquals('123', $result->nit);
        $this->assertEquals('New Company', $result->name);
        $this->assertEquals('Address', $result->address);
        $this->assertEquals('123456', $result->phone);
    }

    public function test_create_company_throws_exception_when_duplicate()
    {
        $data = [
            'nit' => '123',
            'name' => 'New Company',
            'address' => 'Address',
            'phone' => '123456',
        ];

        $company = new Company($data);

        // Arrange
        $this->mockRepository->shouldReceive('findByNit')
            ->with('123')
            ->once()
            ->andReturn($company);

        // Assert & Act
        $this->expectException(DuplicateCompanyException::class);
        $this->service->createCompany($data);
    }

    public function test_update_company()
    {
        // Arrange
        $company = new Company(['nit' => '123456', 'name' => 'Company 1']);

        $data = [
            'name' => 'Updated Company',
            'address' => 'Updated Address',
            'phone' => '1234567890',
        ];

        $updatedCompany = new Company([
            'nit' => '123456',
            'name' => 'Updated Company',
            'address' => 'Updated Address',
            'phone' => '1234567890',
        ]);

        $this->mockRepository->shouldReceive('findByNit')
            ->with('123456')
            ->once()
            ->andReturn($company);

        $this->mockRepository->shouldReceive('update')
            ->with($company, $data)
            ->once()
            ->andReturn($updatedCompany);

        // Act
        $result = $this->service->updateCompany('123456', $data);

        // Assert
        $this->assertEquals('Updated Company', $result->name);
        $this->assertEquals('Updated Address', $result->address);
        $this->assertEquals('1234567890', $result->phone);
    }

    public function test_delete_company()
    {
        // Arrange
        $company = new Company([
            'nit' => '123',
            'name' => 'Company to Delete',
            'status' => CompanyStatus::INACTIVE,
        ]);

        $this->mockRepository->shouldReceive('findByNit')
            ->with('123')
            ->once()
            ->andReturn($company);

        $this->mockRepository->shouldReceive('delete')
            ->with($company)
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->service->deleteCompany('123');

        // Assert
        $this->assertTrue($result);
    }

    public function test_delete_company_throws_exception_if_active()
    {
        // Arrange
        $company = new Company([
            'nit' => '123',
            'name' => 'Active Company',
            'status' => CompanyStatus::ACTIVE,
        ]);

        $this->mockRepository->shouldReceive('findByNit')
            ->with('123')
            ->once()
            ->andReturn($company);

        // Assert & Act
        $this->expectException(CompanyActiveException::class);
        $this->service->deleteCompany('123');
    }

    public function test_delete_inactive_companies()
    {
        // Arrange
        $this->mockRepository->shouldReceive('deleteInactive')
            ->once()
            ->andReturn(5);

        // Act
        $result = $this->service->deleteInactiveCompanies();

        // Assert
        $this->assertEquals(5, $result);
    }
}

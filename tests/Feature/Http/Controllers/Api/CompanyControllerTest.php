<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Enums\CompanyStatus;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_companies()
    {
        // Arrange
        Company::factory()->count(3)->create();

        // Act
        $response = $this->getJson('/api/v1/companies');

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['nit', 'name', 'address', 'phone', 'status']
                ]
            ]);
    }

    public function test_store_creates_new_company()
    {
        // Arrange
        $data = [
            'nit' => '123456789',
            'name' => 'New Test Company',
            'address' => '123 Test Street',
            'phone' => '1234567890',
            'status' => CompanyStatus::ACTIVE->value,
        ];

        // Act
        $response = $this->postJson('/api/v1/companies', $data);

        // Assert
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonFragment([
                'nit' => '123456789',
                'name' => 'New Test Company',
            ]);

        $this->assertDatabaseHas('companies', [
            'nit' => '123456789',
            'name' => 'New Test Company',
        ]);
    }

    public function test_store_validates_input()
    {
        // Arrange
        $data = [
            'nit' => '',  // Invalid: required
            'name' => str_repeat('A', 201),  // Invalid: max 200 chars
            'address' => 'Valid Address',
            'phone' => 'Valid Phone',
        ];

        // Act
        $response = $this->postJson('/api/v1/companies', $data);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['nit', 'name']);
    }

    public function test_show_returns_company()
    {
        // Arrange
        $company = Company::factory()->create(['nit' => '123456789']);

        // Act
        $response = $this->getJson('/api/v1/companies/123456789');

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'nit' => '123456789',
                'name' => $company->name,
            ]);
    }

    public function test_show_returns_404_for_nonexistent_company()
    {
        // Act
        $response = $this->getJson('/api/v1/companies/non-existent-nit');

        // Assert
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_modifies_company()
    {
        // Arrange
        $company = Company::factory()->create(['nit' => '123456789']);

        $data = [
            'name' => 'Updated Company Name',
            'address' => 'Updated Address',
        ];

        // Act
        $response = $this->putJson("/api/v1/companies/{$company->nit}", $data);

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'name' => 'Updated Company Name',
                'address' => 'Updated Address',
            ]);

        $this->assertDatabaseHas('companies', [
            'nit' => '123456789',
            'name' => 'Updated Company Name',
            'address' => 'Updated Address',
        ]);
    }

    public function test_update_validates_input()
    {
        // Arrange
        $company = Company::factory()->create(['nit' => '123456789']);

        $data = [
            'name' => str_repeat('A', 101),  // Invalid: max 100 chars
            'email' => 'invalid-email',  // Invalid: not a valid email
        ];

        // Act
        $response = $this->putJson("/api/v1/companies/{$company->nit}", $data);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'email']);
    }

    public function test_destroy_deletes_inactive_company()
    {
        // Arrange
        $company = Company::factory()->inactive()->create([
            'nit' => '123456789',
        ]);

        // Act
        $response = $this->deleteJson("/api/v1/companies/{$company->nit}");

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['message' => 'Company deleted successfully']);

        $this->assertDatabaseMissing('companies', ['nit' => '123456789']);
    }

    public function test_destroy_returns_error_for_active_company()
    {
        // Arrange
        $company = Company::factory()->active()->create([
            'nit' => '123456789',
        ]);

        // Act
        $response = $this->deleteJson("/api/v1/companies/{$company->nit}");

        // Assert
        $response->assertStatus(Response::HTTP_CONFLICT);
        $this->assertDatabaseHas('companies', ['nit' => '123456789']);
    }

    public function test_delete_inactive_removes_all_inactive_companies()
    {
        // Arrange
        Company::factory()->count(3)->active()->create();
        Company::factory()->count(2)->inactive()->create();

        // Act
        $response = $this->deleteJson('/api/v1/companies/inactive');

        // Assert
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment([
                'message' => '2 inactive companies have been deleted',
                'count' => 2
            ]);

        $this->assertDatabaseCount('companies', 3);
        $this->assertDatabaseMissing('companies', ['status' => CompanyStatus::INACTIVE]);
    }
}

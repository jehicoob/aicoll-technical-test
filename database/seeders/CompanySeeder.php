<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 random companies, could be active or inactive
        Company::factory(10)->create();

        // Create 3 explicitly active companies
        Company::factory(3)
            ->active()
            ->create();

        // Create 2 explicitly inactive companies
        Company::factory(2)
            ->inactive()
            ->create();
    }
}

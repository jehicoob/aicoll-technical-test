<?php

namespace Database\Factories;

use App\Enums\CompanyStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nit' => $this->faker->unique()->numerify('##########'),
            'name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement([CompanyStatus::ACTIVE, CompanyStatus::INACTIVE]),
        ];
    }

    /**
     * Indicate that the company is active.
     *
     * @return static
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => CompanyStatus::ACTIVE
        ]);
    }

    /**
     * Indicate that the company is inactive.
     *
     * @return static
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => CompanyStatus::INACTIVE
        ]);
    }
}

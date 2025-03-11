<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomDate = Carbon::now()->subYear()->addDays(rand(0, 365));
        return [
            'title' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'for_sale' => fake()->boolean(),
            'for_rent' => fake()->boolean(),
            'sold' => fake()->boolean(),
            'price' => fake()->numberBetween(10000, 100000),
            'currency' => 'THB',
            'currency_symbol' => '฿',
            'property_type' => fake()->randomElement(['Condo', 'Apartment', 'House', 'Villa']),
            'bedrooms' => fake()->numberBetween(1, 5),
            'bathrooms' => fake()->numberBetween(1, 5),
            'area' => fake()->numberBetween(30, 500),
            'area_type' => 'sqm',
            'created_at' => $randomDate,
            'updated_at' => $randomDate,
        ];
    }
}

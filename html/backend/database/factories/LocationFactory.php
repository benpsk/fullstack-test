<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
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
            'property_id' => Property::factory(),
            'country' => 'Thailand',
            'province' => fake()->randomElement(['Bangkok', 'Phuket', 'Chiang Mai', 'Samut Prakan']),
            'street' => fake()->address(),
            'created_at' => $randomDate,
            'updated_at' => $randomDate,
        ];
    }
}

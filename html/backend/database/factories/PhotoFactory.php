<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
 */
class PhotoFactory extends Factory
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
            'thumb' => 'https://placehold.co/150x100',
            'search' => 'https://placehold.co/300x150',
            'full' => 'https://placehold.co/600x300',
            'created_at' => $randomDate,
            'updated_at' => $randomDate,
        ];
    }
}

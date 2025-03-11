<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\Photo;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PropertyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_correct_pagination()
    {
        Property::factory()
        ->count(100)
        ->has(Location::factory(), 'location')
        ->has(Photo::factory(), 'photo')
        ->create();
        $response = $this->getJson('/api/properties');
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'location',
                        'photo'
                    ]
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            ])
            ->assertJsonPath('meta.per_page', 25)
            ->assertJsonPath('meta.total', 100);
    }

    public function test_it_filters_properties_by_province()
    {
        Location::factory(10)->create(['province' => 'Bangkok']);
        Location::factory(10)->create(['province' => 'Chiang Mai']);

        $query = http_build_query(['province' => 'Bangkok']);
        $response = $this->getJson("/api/properties?$query");
        $response->assertStatus(200)
            ->assertJsonFragment([
                'province' => 'Bangkok'
            ])
            ->assertJsonPath('meta.to', 10)
            ->assertJsonPath('meta.total', 10);
    }
    public function test_it_filters_within_specific_province()
    {
        Property::factory()->count(10)
            ->has(Location::factory()->state(['province' => 'Bangkok']), 'location')
            ->create(['title' => 'hello']); // pass

        Property::factory()->count(10)
            ->has(Location::factory()->state(['province' => 'Bangkok']), 'location')
            ->create(['title' => 'hella']); // pass

        Property::factory()->count(10)
            ->has(Location::factory()->state(['province' => 'Chiang Mai']), 'location')
            ->create(['title' => 'hello']); // Fail

        Property::factory()->count(10)
            ->has(Location::factory()->state(['province' => 'Phuket']), 'location')
            ->create(['title' => 'hella']); // Fail

        $query = http_build_query(['province' => 'Bangkok', 'title' => 'hel']);
        $response = $this->getJson("/api/properties?$query");
        $response->assertStatus(200)
            ->assertJsonFragment([
                'province' => 'Bangkok'
            ])
            ->assertJsonPath('meta.to', 20)
            ->assertJsonPath('meta.total', 20);

    }
    public static function titleData(): array
    {
        return [
            [
                'input' => [
                    'title' => 'Luxury Condo',
                    'keyword' => 'Luxury',
                    'actual' => 20,
                    'others' => 100,
                ],
                'expected' => ['to' => 20, 'total' => 20]
            ],
            [
                'input' => [
                    'title' => 'Bangkok for', // title also check for province
                    'keyword' => 'Bangkok',
                    'actual' => 30,
                    'others' => 100,
                ],
                'expected' => ['to' => 25, 'total' => 40]
            ],
        ];
    }
    #[DataProvider('titleData')]
    public function test_it_filters_properties_by_title($input, $expected)
    {
        Property::factory($input['actual'])->create(['title' => $input['title']]);
        Property::factory($input['others'])->create();
        Property::factory()->count(10)
            ->has(Location::factory()->state(['province' => 'Bangkok']), 'location')
            ->create();// title => Bangkok should pass

        $query = http_build_query(['title' => $input['keyword']]);
        $response = $this->getJson("/api/properties?$query");
        $response->assertStatus(200)
            ->assertJsonFragment(['title' => $input['title']])
            ->assertJsonPath('meta.to', $expected['to'])
            ->assertJsonPath('meta.total', $expected['total']);
    }
    public static function priceSortData(): array
    {
        return [
            [
                'input' => [
                    'order' => 'asc',
                ],
                'expected' => ['price1' => 100, 'price2' => 200]
            ],
            [
                'input' => [
                    'order' => 'desc',
                ],
                'expected' => ['price1' => 200, 'price2' => 100]
            ],
        ];
    }
    #[DataProvider('priceSortData')]
    public function test_it_sorts_properties_by_price($input, $expected)
    {
        Property::factory()->create(['price' => 100]);
        Property::factory()->create(['price' => 200]);
        $query = http_build_query([
            'order_by' => 'price',
            'order' => $input['order'],
        ]);
        $response = $this->getJson("/api/properties?$query");
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals($data[0]['price'], $expected['price1']);
        $this->assertEquals($data[1]['price'], $expected['price2']);
    }
    public static function dateSortData(): array
    {
        return [
            [
                'input' => [
                    'order' => 'asc',
                ],
                'expected' => ['date1' => '2024-01-01 00:00:00', 'date2' => '2025-01-01 00:00:00']
            ],
            [
                'input' => [
                    'order' => 'desc',
                ],
                'expected' => ['date1' => '2025-01-01 00:00:00', 'date2' => '2024-01-01 00:00:00']
            ],
        ];
    }
    #[DataProvider('dateSortData')]
    public function test_it_sorts_properties_by_date($input, $expected)
    {
        Property::factory()->create(['created_at' => '2025-01-01 00:00:00']);
        Property::factory()->create(['created_at' => '2024-01-01 00:00:00']);
        $query = http_build_query([
            'order_by' => 'created_at',
            'order' => $input['order'],
        ]);
        $response = $this->getJson("/api/properties?$query");
        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertEquals($data[0]['created_at'], $expected['date1']);
        $this->assertEquals($data[1]['created_at'], $expected['date2']);
    }
    public static function invalidSortingData(): array
    {
        return [
            [
                'input' => [
                    'order' => 'apk',
                    'order_by' => 'name',
                    'title' => fake()->paragraph(10),
                    'province' => 'hello'
                ],
                'errors' => ['order', 'order_by', 'title', 'province']
            ],
            [
                'input' => [
                    'order' => 'desc',
                    'order_by' => 'price',
                    'title' => fake()->paragraph(10),
                    'province' => 'Bangkok'
                ],
                'errors' => ['title']
            ],
            [
                'input' => [
                    'order' => 'dd',
                    'order_by' => 'price',
                    'title' => 'hello',
                    'province' => 'Bangkok'
                ],
                'errors' => ['order']
            ],
            [
                'input' => [
                    'order' => 'asc',
                    'order_by' => 'dkd',
                    'title' => 'hello',
                    'province' => 'Bangkok'
                ],
                'errors' => ['order_by']
            ],
        ];
    }
    #[DataProvider('invalidSortingData')]
    public function test_it_returns_validation_errors_for_invalid_params($input, $errors)
    {
        $query = http_build_query([
            'order_by' => $input['order_by'],
            'order' => $input['order'],
            'title' => $input['title'],
            'province' => $input['province']
        ]);
        $response = $this->getJson("/api/properties?$query");
        $response->assertStatus(422)
            ->assertJsonValidationErrors($errors);
    }
    public function test_it_return_correct_per_page_pagination()
    {
        Property::factory()->count(30)->create();
        $query = http_build_query([
            'per_page' => 10,
        ]);
        $response = $this->getJson("/api/properties?$query");
        $response->assertStatus(200)
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonPath('meta.total', 30);
    }
}

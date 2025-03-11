<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('properties.json');

        // TODO: implement memory efficient way. [chunking reader ??]
        $data = json_decode(file_get_contents($filePath), true);

        $batchSize = 500;
        $chunks = array_chunk($data, $batchSize);

        foreach ($chunks as $batch) {
            $properties = [];
            $locations = [];
            $photos = [];

            foreach ($batch as $item) {
                $randomDate = Carbon::now()->subYear()->addDays(rand(0, 365));

                $propertyId = $item['id'];

                $properties[] = [
                    'id' => $propertyId,
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'for_sale' => $item['for_sale'],
                    'for_rent' => $item['for_rent'],
                    'sold' => $item['sold'],
                    'price' => $item['price'],
                    'currency' => $item['currency'],
                    'currency_symbol' => $item['currency_symbol'],
                    'property_type' => $item['property_type'],
                    'bedrooms' => $item['bedrooms'],
                    'bathrooms' => $item['bathrooms'],
                    'area' => $item['area'],
                    'area_type' => $item['area_type'],
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ];

                $locations[] = [
                    'property_id' => $propertyId,
                    'country' => $item['geo']['country'],
                    'province' => $item['geo']['province'],
                    'street' => $item['geo']['street'],
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ];

                $photos[] = [
                    'property_id' => $propertyId,
                    'thumb' => $item['photos']['thumb'],
                    'search' => $item['photos']['search'],
                    'full' => $item['photos']['full'],
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ];
            }

            DB::table('properties')->insert($properties);
            DB::table('locations')->insert($locations);
            DB::table('photos')->insert($photos);
        }
    }
}

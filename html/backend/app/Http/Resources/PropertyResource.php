<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'for_sale' => $this->for_sale,
            'for_rent' => $this->for_rent,
            'sold' => $this->sold,
            'price' => $this->price,
            'currency' => $this->currenty,
            'currency_symbol' => $this->currency_symbol,
            'property_type' => $this->property_type,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'area' => $this->area,
            'area_type' => $this->area_type,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'location' => new LocationResource($this->whenLoaded('location')),
            'photo' => new PhotoResource($this->whenLoaded('photo')),
        ];
    }
}

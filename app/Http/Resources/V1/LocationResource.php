<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
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
            'name' => $this->name,
            'historical_names' => $this->historical_names ?? [],
            'state' => $this->state,
            'district' => $this->district,
            'taluk_tehsil' => $this->taluk_tehsil,
            'coordinates' => [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'altitude_meters' => $this->altitude_meters,
                'uncertainty_radius_meters' => $this->uncertainty_radius_meters,
            ],
            'description' => $this->description,
        ];
    }
}

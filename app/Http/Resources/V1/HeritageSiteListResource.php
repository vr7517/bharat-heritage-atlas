<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeritageSiteListResource extends JsonResource
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
            'slug' => $this->slug,
            'site_type' => $this->site_type,
            'dating_statement' => $this->dating_statement,
            'chronology' => [
                'start_year' => $this->start_year,
                'end_year' => $this->end_year,
                'is_dating_uncertain' => (bool) $this->is_dating_uncertain,
            ],
            'protection_status' => $this->protection_status,
            'summary' => $this->summary,
            'location' => $this->whenLoaded('location', function () {
                return [
                    'id' => $this->location->id,
                    'name' => $this->location->name,
                    'state' => $this->location->state,
                    'district' => $this->location->district,
                    'latitude' => $this->location->latitude,
                    'longitude' => $this->location->longitude,
                ];
            }),
            'period' => $this->whenLoaded('primaryPeriod', function () {
                return [
                    'id' => $this->primaryPeriod->id,
                    'name' => $this->primaryPeriod->name,
                    'slug' => $this->primaryPeriod->slug,
                ];
            }),
            'dynasty' => $this->whenLoaded('primaryDynasty', function () {
                return [
                    'id' => $this->primaryDynasty->id,
                    'name' => $this->primaryDynasty->name,
                    'slug' => $this->primaryDynasty->slug,
                ];
            }),
            'counts' => [
                'objects' => $this->objects_count ?? $this->objects->count(),
                'inscriptions' => $this->inscriptions_count ?? $this->inscriptions->count(),
                'claims' => $this->claims_count ?? $this->claims->count(),
            ],
        ];
    }
}

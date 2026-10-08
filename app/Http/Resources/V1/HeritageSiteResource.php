<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeritageSiteResource extends JsonResource
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
            'historical_context' => $this->historical_context,
            'architectural_description' => $this->architectural_description,
            'location' => new LocationResource($this->whenLoaded('location')),
            'primary_period' => new PeriodResource($this->whenLoaded('primaryPeriod')),
            'primary_dynasty' => new DynastyResource($this->whenLoaded('primaryDynasty')),
            'objects' => HeritageObjectListResource::collection($this->whenLoaded('objects')),
            'inscriptions' => InscriptionResource::collection($this->whenLoaded('inscriptions')),
            'claims' => ClaimResource::collection($this->whenLoaded('claims')),
        ];
    }
}

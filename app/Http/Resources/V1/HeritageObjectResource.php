<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeritageObjectResource extends JsonResource
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
            'object_type' => $this->object_type,
            'material' => $this->material,
            'dimensions' => $this->dimensions,
            'current_repository' => $this->current_repository,
            'accession_number' => $this->accession_number,
            'dating_statement' => $this->dating_statement,
            'chronology' => [
                'start_year' => $this->start_year,
                'end_year' => $this->end_year,
                'is_dating_uncertain' => (bool) $this->is_dating_uncertain,
            ],
            'description' => $this->description,
            'iconographic_notes' => $this->iconographic_notes,
            'site' => $this->whenLoaded('heritageSite', function () {
                return [
                    'id' => $this->heritageSite->id,
                    'name' => $this->heritageSite->name,
                    'slug' => $this->heritageSite->slug,
                    'site_type' => $this->heritageSite->site_type,
                ];
            }),
            'location' => new LocationResource($this->whenLoaded('location')),
            'period' => new PeriodResource($this->whenLoaded('period')),
            'dynasty' => new DynastyResource($this->whenLoaded('dynasty')),
            'inscriptions' => InscriptionResource::collection($this->whenLoaded('inscriptions')),
            'claims' => ClaimResource::collection($this->whenLoaded('claims')),
        ];
    }
}

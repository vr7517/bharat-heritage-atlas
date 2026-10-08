<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeritageObjectListResource extends JsonResource
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
            'site' => $this->whenLoaded('heritageSite', function () {
                return [
                    'id' => $this->heritageSite->id,
                    'name' => $this->heritageSite->name,
                    'slug' => $this->heritageSite->slug,
                ];
            }),
            'period' => $this->whenLoaded('period', function () {
                return [
                    'id' => $this->period->id,
                    'name' => $this->period->name,
                    'slug' => $this->period->slug,
                ];
            }),
            'dynasty' => $this->whenLoaded('dynasty', function () {
                return [
                    'id' => $this->dynasty->id,
                    'name' => $this->dynasty->name,
                    'slug' => $this->dynasty->slug,
                ];
            }),
            'counts' => [
                'inscriptions' => $this->inscriptions_count ?? $this->inscriptions->count(),
                'claims' => $this->claims_count ?? $this->claims->count(),
            ],
        ];
    }
}

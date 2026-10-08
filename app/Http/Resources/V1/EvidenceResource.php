<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvidenceResource extends JsonResource
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
            'evidence_type' => $this->evidence_type,
            'classification' => $this->classification,
            'description' => $this->description,
            'stratigraphic_context' => $this->stratigraphic_context,
            'methodology_applied' => $this->methodology_applied,
            'uncertainty_notes' => $this->uncertainty_notes,
            'relationship' => $this->whenPivotLoaded('claim_evidence', function () {
                return [
                    'relationship_type' => $this->pivot->relationship_type,
                    'scholarly_weight' => $this->pivot->scholarly_weight,
                    'analysis_notes' => $this->pivot->analysis_notes,
                ];
            }),
            'sources' => SourceResource::collection($this->whenLoaded('sources')),
        ];
    }
}

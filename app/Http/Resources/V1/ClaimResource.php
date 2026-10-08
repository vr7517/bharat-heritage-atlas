<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClaimResource extends JsonResource
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
            'statement' => $this->statement,
            'claim_type' => $this->claim_type,
            'consensus_status' => $this->consensus_status,
            'summary_justification' => $this->summary_justification,
            'entity' => $this->whenLoaded('claimable', function () {
                return [
                    'type' => class_basename($this->claimable_type),
                    'id' => $this->claimable_id,
                    'name' => $this->claimable?->name ?? $this->claimable?->title,
                    'slug' => $this->claimable?->slug,
                ];
            }),
            'evidence' => EvidenceResource::collection($this->whenLoaded('evidence')),
        ];
    }
}

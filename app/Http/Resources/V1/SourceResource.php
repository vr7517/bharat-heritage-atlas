<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SourceResource extends JsonResource
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
            'authors' => $this->authors,
            'publication_year' => $this->publication_year,
            'source_type' => $this->source_type,
            'publisher' => $this->publisher,
            'journal_or_series' => $this->journal_or_series,
            'volume_issue' => $this->volume_issue,
            'pages' => $this->pages,
            'isbn_issn' => $this->isbn_issn,
            'url' => $this->url,
            'reliability_tier' => $this->reliability_tier,
            'notes' => $this->notes,
            'citation' => $this->whenPivotLoaded('evidence_source', function () {
                return [
                    'specific_pages' => $this->pivot->specific_pages,
                    'direct_quotation_or_data' => $this->pivot->direct_quotation_or_data,
                    'citation_context' => $this->pivot->citation_context,
                ];
            }),
            'evidence_count' => $this->evidence_count ?? $this->whenLoaded('evidence', fn () => $this->evidence->count()),
            'evidence' => EvidenceResource::collection($this->whenLoaded('evidence')),
        ];
    }
}

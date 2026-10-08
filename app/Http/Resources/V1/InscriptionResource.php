<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InscriptionResource extends JsonResource
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
            'slug' => $this->slug,
            'language' => $this->language,
            'script' => $this->script,
            'dating_statement' => $this->dating_statement,
            'chronology' => [
                'start_year' => $this->start_year,
                'end_year' => $this->end_year,
            ],
            'donor' => $this->donor,
            'ruler_mentioned' => $this->ruler_mentioned,
            'epigraphic_reference' => $this->epigraphic_reference,
            'raw_text' => $this->raw_text,
            'translation' => $this->translation,
            'interpretation_notes' => $this->interpretation_notes,
            'site' => $this->whenLoaded('heritageSite', function () {
                return [
                    'id' => $this->heritageSite->id,
                    'name' => $this->heritageSite->name,
                    'slug' => $this->heritageSite->slug,
                ];
            }),
            'object' => $this->whenLoaded('object', function () {
                return [
                    'id' => $this->object->id,
                    'name' => $this->object->name,
                    'slug' => $this->object->slug,
                ];
            }),
            'claims' => ClaimResource::collection($this->whenLoaded('claims')),
        ];
    }
}

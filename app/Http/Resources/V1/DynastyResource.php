<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DynastyResource extends JsonResource
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
            'dating_statement' => $this->dating_statement,
            'chronology' => [
                'start_year' => $this->start_year,
                'end_year' => $this->end_year,
            ],
            'region' => $this->region,
            'capital' => $this->capital,
            'description' => $this->description,
        ];
    }
}

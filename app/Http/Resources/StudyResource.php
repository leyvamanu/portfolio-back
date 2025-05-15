<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'specialty' => $this->specialty,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'institution' => $this->institution,
            'image' => $this->image,
        ];
    }
}

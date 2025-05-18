<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'image' => $this->image,
            'short_desc' => $this->short_desc,
            'full_desc' => $this->full_desc,
            'type' => $this->type,
            'github' => $this->github,
            'url' => $this->url,
            'featured' => $this->featured,
            'skills' => $this->skills->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'name' => $skill->name,
                    'icon' => $skill->icon,
                ];
            }),
        ];
    }
}

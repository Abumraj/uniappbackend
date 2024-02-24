<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
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
            'schoolName' => $this->name,
            'schoolUrl' => $this->url,
            'schoolPortalUrl' => $this->portalurl,
            'sugUrl' => $this->sugurl,
            'sugName' => $this->sugname,
            'schoolImageUrl' => $this->image_url,
            'isActive' => $this->is_active,
        ];
    }
}

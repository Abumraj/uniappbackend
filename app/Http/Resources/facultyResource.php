<?php

namespace App\Http\Resources;
use App\Models\faculty;

use Illuminate\Http\Resources\Json\JsonResource;

class facultyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'facultyId' => $this->id,
            'facultyName' => $this->name,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    const UNDERGRADUATE_PLAN = 1;
    const REMEDIAL_PLAN = 2;
    const ASPIRANT_PLAN = 3;
    const POSTUTME_PLAN = 4;
    const JUPEB_PLAN = 5;
    const IJMB_PLAN = 6;
    const SANDWICH_PLAN = 7;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'departmentId' => $this->id,
            'facultyId' => $this->faculty_id,
            'departmentName' => $this->name,
        ];
    }
}

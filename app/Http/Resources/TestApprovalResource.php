<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Department;
class TestApprovalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $depart = Department::where('user_id', $this->id)->select('name')->get();
        return [
            'id' => $this->id,
            'fullName' => $this->name,
            'imageUrl' => $this->imageUrl,
            'matricNo' => $this->matric_number,
            'department' => Department::find($this->department_id)->name,
            'level' => $this->level,
            'isPublished' => $this->pivot->is_approved,
        ];
    }
}

<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Department;
class LecturerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request )
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'fullName' => $this->name,
            'imageUrl' => $this->imageUrl,
            'email' => $this->email,
            'matricNo' => $this->matric_number,
            'phoneNo' => $this->phone,
            'department' => Department::find($this->department_id)->name,
            'sex' => $this->sex,


        ];
    }
}

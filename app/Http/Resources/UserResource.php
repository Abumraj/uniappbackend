<?php

namespace App\Http\Resources;
use App\Models\Department;
use App\Models\Level;
use App\Models\Subscription;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'fullName' => $this->name,
            'imageUrl' => $this->imageUrl,
            'email' => $this->email,
            'matricNo' => $this->matric_number,
            'phoneNo' => $this->phone,
            'department' =>  $this->department_d == null ? "Aspirant" : Department::find( $this->department_d)->name,
            'sex' => $this->sex,
            'level' => $this->level_id == null ?  "Into"  : Level::find($this->level_id)->name,
            'subscription' => Subscription::find($this->subscription_id)->name


        ];
    }
}

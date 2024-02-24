<?php

namespace App\Http\Resources;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)

    {

        $user = User::find(intval($this->user_id));
        return [

            'id' => $this->id,
            'fullName' => $user->name,
            'imageUrl' => $user->imageUrl,
            'matricNo' => $user->matric_number,
            'score' => $this->score,
        ];
    }
}

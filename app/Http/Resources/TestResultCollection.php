<?php

namespace App\Http\Resources;
use App\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TestResultCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $user = User::find(intval($this->user_id));
        // $matricNo = $user->matric_number;
        // $name = $user->name;
        // $image = $user->imageUrl;
        return [

           TestResultResource::collection($this->collection)
            // 'fullName' => $this->user_id,
            // 'imageUrl' => $this->user_id,
            // 'matricNo' => $this->test_id,
            // 'score' => $this->collection->score,
        ];    }
}

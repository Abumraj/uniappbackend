<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Course;

class optionResource extends JsonResource
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
            'liveId' => $this->id,
            'courseCode' =>   Course::where('id',$this->course_id)->select('code')->first()->code,
            'title' => $this->title,
            'subtitle'  => $this->subtitle,
            'live_url' => $this->live_url,
            'status' => $this->status,
            'chatLink' => $this->chatLink,
            'start_at' => $this->start_at,
        ];
    }
}

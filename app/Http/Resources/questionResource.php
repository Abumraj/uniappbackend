<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class questionResource extends JsonResource
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
            'courseId' => $this->course_id,
            'chapterId' => $this->chapter_id,
            'question' => $this->question,
            'imageUrl' => $this->imageUrl,
            'option1' => $this->answer,
            'option2' => $this->option2,
            'option3' => $this->option3,
            'option4' => $this->option4,
            'solution'  => $this->solution,
            'isPublished' => $this->is_published,
        ];
    }
}

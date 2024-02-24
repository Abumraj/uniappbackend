<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestQuestionResource extends JsonResource
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
            'chapterId' => $this->test_id,
            'question' => $this->question,
            'imageUrl' => $this->imageUrl,
            'option1' => $this->answer,
            'option2' => $this->option2,
            'option3' => $this->option3,
            'option4' => $this->option4,
            'isPublished' => $this->is_published,
        ];
    }
}

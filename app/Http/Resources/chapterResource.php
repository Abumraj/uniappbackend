<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class chapterResource extends JsonResource
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
        $title = $user->title;
        $name = $user->name;
        return
        [
            'chapterId' => $this->id,
            'courseId' => intval($this->course_id),
            'userId' =>  "$title $name",
            'chapterName' => $this->name,
            'chapterDescrip' => $this->description,
            'chapterOrderId' => intval($this->orderId),
            'quesNum' => $this->quesnum,
            'quesTime' => $this->questime,
            'isPublished' => $this->is_published,
        ];
    }
}

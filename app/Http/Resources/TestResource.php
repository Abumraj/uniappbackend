<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class TestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $id = Auth::user()->id;
        $user = User::find(intval($this->user_id));
        $title = $user->title;
        $name = $user->name;
        return [
            'chapterId' => $this->id,
            'courseId' => intval($this->course_id),
            'chapterName' => $this->name,
            'chapterDescrip' => $this->description,
            'marks' => $this->mark,
            'quesNum' => $this->quesNum,
            'quesTime' => $this->quesTime,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'isPublished' => $this->is_published,
            'isStarted' => $this->is_started,
            'lecturer' => $id == $this->user_id ? "You": "$title $name",
        ];
    }
}

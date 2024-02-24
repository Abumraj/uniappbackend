<?php

namespace App\Http\Resources;

use App\Models\Level;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;
class CourseResource extends JsonResource
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
            'courseId' =>intVal($this->id),
            'courseName' =>$this->title,
            'coursecode' =>$this->code,
            'semester' =>intval($this->semester),
            'status' =>$this->status,
            'unit' =>intval($this->unit),
            'level' =>Level::find($this->level_id)->name,
            'type' =>$this->type,
            'courseChatLink' =>$this->chatLink,
            'isPublished' =>intVal($this->is_published),
            'isFullAccess' =>$this->pivot->is_full_access,
            'role' => $this->pivot->role,
            // 'expireAt' => $this->pivot->expire_at,

        ];

}

}

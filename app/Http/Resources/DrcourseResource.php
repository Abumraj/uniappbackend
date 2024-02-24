<?php

namespace App\Http\Resources;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DrcourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
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
            // 'isFullAccess' =>$this->when($this->pivot->is_full_access !=null,  $this->pivot->is_full_access ),
            // 'role' => $this->pivot->role,
            // 'expireAt' => $this->pivot->expire_at,

        ];
    }
}

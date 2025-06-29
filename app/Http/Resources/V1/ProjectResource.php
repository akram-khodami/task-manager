<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'status_title' => $this->status_title,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'priority' => $this->priority,
            'priority_title' => $this->priority_title,
            'owner_name'=>$this->owner->name,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'created_at' => $this->created_at,//تاریخ شمسی؟؟؟
            'updated_at' => $this->updated_at,//تاریخ شمسی؟؟؟
        ];
    }
}

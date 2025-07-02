<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'status_title' => $this->status_title,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'priority_title' => $this->priority_title,
            'folder' => new FolderResource($this->whenLoaded('folder')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'assigned_to' => new UserResource($this->whenLoaded('assignedUser')),
            'attachments' => TaskAttachmentResource::collection($this->whenLoaded('attachments')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
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
            'project' => new ProjectResource($this->whenLoaded('project')),
            'parent' => new FolderResource($this->whenLoaded('parent')),
            'children' => FolderResource::collection($this->whenLoaded('children')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'task_count' => $this->when(isset($this->task_count), $this->task_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 

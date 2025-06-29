<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskAttachmentResource extends JsonResource
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
            'file_name' => $this->file_name,
            'file_type' => $this->file_type,
            'file_size' => $this->file_size,
            'file_path' => $this->file_path,
            'uploaded_by' => new UserResource($this->whenLoaded('uploadedBy')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 
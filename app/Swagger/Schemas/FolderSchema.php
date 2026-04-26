<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Folder",
 *     type="object",
 *     title="Folder",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Sprint 1"),
 *     @OA\Property(property="project", ref="#/components/schemas/Project"),
 *     @OA\Property(property="parent", ref="#/components/schemas/Folder"),
 *     @OA\Property(property="children", type="array", @OA\Items(ref="#/components/schemas/Folder")),
 *     @OA\Property(property="tasks", type="array", @OA\Items(ref="#/components/schemas/Task")),
 *     @OA\Property(property="task_count", type="integer", example=5),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T10:19:07.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T10:19:07.000000Z"),
 * )
 */
class FolderSchema
{
    //
}

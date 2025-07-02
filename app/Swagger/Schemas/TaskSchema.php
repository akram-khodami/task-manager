<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     title="Task",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Implement login"),
 *     @OA\Property(property="description", type="string", example="Implement the login functionality for the app"),
 *     @OA\Property(property="status", type="string", enum={"pending","in_progress","done"}, example="pending"),
 *     @OA\Property(property="status_title", type="string", example="Pending"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-06-10"),
 *     @OA\Property(property="priority", type="string", example="high"),
 *     @OA\Property(property="priority_title", type="string", example="High"),
 *     @OA\Property(property="folder", ref="#/components/schemas/Folder"),
 *     @OA\Property(property="creator", ref="#/components/schemas/User"),
 *     @OA\Property(property="assigned_to", ref="#/components/schemas/User"),
 *     @OA\Property(property="attachments", type="array", @OA\Items(type="object")),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T10:19:07.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T10:19:07.000000Z"),
 * )
 */
class TaskSchema
{
    //
} 
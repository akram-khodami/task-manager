<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Role",
 *     type="object",
 *     title="Role",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Manager"),
 *     @OA\Property(property="description", type="string", example="Manages projects and tasks"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_by", ref="#/components/schemas/User"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T10:19:07.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T10:19:07.000000Z"),
 * )
 */
class RoleSchema
{
    //
} 
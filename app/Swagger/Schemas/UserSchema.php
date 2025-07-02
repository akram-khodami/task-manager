<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T10:19:07.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T10:19:07.000000Z"),
 *     @OA\Property(
 *         property="roles",
 *         type="array",
 *         @OA\Items(type="string", example="admin"),
 *         example={"admin","user"}
 *     ), 
 * )
 */
abstract class UserSchema
{
    //
} 
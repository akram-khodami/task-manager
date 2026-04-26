<?php


namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Project",
 *     type="object",
 *     title="Project",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="ORIGINAL"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T10:19:07.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T10:19:07.000000Z"),
 * @OA\Property(
 *     property="deleted_at",
 *     oneOf={@OA\Schema(type="string", format="date-time"), @OA\Schema(type="null")},
 *     example=null
 *     ),
 *  )
 */
abstract class ProjectSchema
{

}

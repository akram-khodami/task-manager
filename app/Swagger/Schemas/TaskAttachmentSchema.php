<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="TaskAttachment",
 *     type="object",
 *     title="TaskAttachment",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="file_name", type="string", example="spec.pdf"),
 *     @OA\Property(property="file_type", type="string", example="application/pdf"),
 *     @OA\Property(property="file_size", type="integer", example=204800),
 *     @OA\Property(property="file_path", type="string", example="/uploads/spec.pdf"),
 *     @OA\Property(property="uploaded_by", ref="#/components/schemas/User"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-17T10:19:07.000000Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-17T10:19:07.000000Z"),
 * )
 */
class TaskAttachmentSchema
{
    //
} 
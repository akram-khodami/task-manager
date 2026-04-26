<?php


namespace App\Http\Controllers;


/**
 * @OA\Info(
 *     title="Task Manager API",
 *     version="1.0.0",
 *     description="Task Manager documentations",
 *     @OA\Contact(email="akram.khodami@gamil.com"),
 *     @OA\License(name="MIT")
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Use a valid token from /api/auth/login or /api/auth/register",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 *
 * @OA\Tag(
 *     name="auth",
 *     description="Authentication endpoints"
 * )
 */
class SwaggerController extends Controller
{
    //
}

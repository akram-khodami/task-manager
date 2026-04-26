<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreRoleRequest;
use App\Http\Requests\V1\UpdateRoleRequest;
use App\Http\Resources\V1\RoleResource;
use App\Models\Role;

/**
 * @OA\Tag(
 *     name="roles",
 *     description="Role management endpoints"
 * )
 */
class RoleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/roles",
     *     tags={"roles"},
     *     summary="List roles",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of roles",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Role"))
     *     )
     * )
     */
    public function index()
    {
        $roles = Role::paginate();

        return RoleResource::collection($roles);
    }

    /**
     * @OA\Post(
     *     path="/api/roles",
     *     tags={"roles"},
     *     summary="Create a role",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Manager"),
     *             @OA\Property(property="description", type="string", example="Manages projects and tasks"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Role created",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = auth()->id();

        $role = Role::create($data);

        return response()->json(new RoleResource($role), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     tags={"roles"},
     *     summary="Show a role",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Role details",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */
    public function show(Role $role)
    {
        return response()->json(new RoleResource($role));
    }

    /**
     * @OA\Put(
     *     path="/api/roles/{id}",
     *     tags={"roles"},
     *     summary="Update a role",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Manager"),
     *             @OA\Property(property="description", type="string", example="Manages projects and tasks"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Role updated",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $data = $request->validated();

        $data['updated_by'] = auth()->id();

        $role->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully.',
            'data' => new RoleResource($role)
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/roles/{id}",
     *     tags={"roles"},
     *     summary="Delete a role",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Role deleted"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to delete role"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found"
     *     )
     * )
     */
    public function destroy(Role $role)
    {
        if ($role->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to delete role.',
        ], 500);
    }
}

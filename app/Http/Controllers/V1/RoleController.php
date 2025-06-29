<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreRoleRequest;
use App\Http\Requests\V1\UpdateRoleRequest;
use App\Http\Resources\V1\RoleResource;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::paginate();

        return response()->json(RoleResource::collection($roles));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = auth()->id();

        $role = Role::create($data);

        return response()->json(new RoleResource($role), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return response()->json(new RoleResource($role));
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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

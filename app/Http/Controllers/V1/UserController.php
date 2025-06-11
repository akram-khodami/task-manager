<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreUserRequest;
use App\Http\Requests\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);

        return response()->json(UserResource::collection($users));

    }

    public function availableRoles()
    {
        return response()->json(
            [
                'roles' => Role::all()
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {

        $user_data = $request->validated();

        $user = DB::transaction(function () use ($user_data) {

            $user = User::create(
                [
                    'name' => $user_data['name'],
                    'email' => $user_data['email'],
                    'password' => Hash::make($user_data['password']),
                ]);

            $user->roles()->attach($user_data['roles']);

            return $user;

        });

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => new UserResource($user)
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user_data = $request->validated();

        DB::transaction(function () use ($user_data, $user) {

            $updateData = [
                'name' => $user_data['name'],
                'email' => $user_data['email'],
            ];

            if (!empty($user_data['password'])) {

                $updateData['password'] = Hash::make($user_data['password']);

            }

            $user->update($updateData);

            $user->roles()->sync($user_data['roles']);

        });

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => new UserResource($user)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {

            $user->roles()->detach();

            $user->delete();

        });

        return response()->json(
            [
                'success' => true,
                'message' => 'User deleted successfully.',
            ], 200);

    }
}

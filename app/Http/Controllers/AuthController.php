<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\AuthTrait;

class AuthController extends Controller
{
    use AuthTrait;

    public function register(RegisterRequest $request)
    {
        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]
        );

        $tokenResult = $this->generateToken($user);

        return response()->json(
            [
                'message' => 'User is registerd successfully.',
                'access_token' => $tokenResult->plainTextToken,
                'token_type' => 'Bearer',
                'expires_at' => $tokenResult->accessToken->expires_at,
                'user' => $user,
            ],
            201
        );
    }

    //===باید مثل تمام فروشگاه ها با موبایل یا ایمیل امکان پذیر باشد.
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return response()->json(
                [
                    'message' => 'The provided credentials are incorrect.',
                    'error' => 'Unauthorized'
                ],
                401
            );
        }

        $tokenResult = $this->generateToken($user);

        return response()->json(
            [
                'message' => 'Logged in successfully.',
                'access_token' => $tokenResult->plainTextToken,
                'token_type' => 'Bearer',
                'expires_at' => $tokenResult->accessToken->expires_at,
                'user' => $user,
            ],
            200
        );
    }

    public function user()
    {
        return new UserResource(Auth()->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(
            [
                'message' => 'Logout successfully.'
            ],
            200
        );
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(
            [
                'message' => 'Logged out from all devices'
            ],
            200
        );
    }
}

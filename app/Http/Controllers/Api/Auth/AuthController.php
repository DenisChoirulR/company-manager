<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'These credentials do not match our records'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('user')->plainTextToken;

        return response()->json([
            'message' => 'Login Success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => new UserResource($user)
            ]
        ]);
    }

    public function getUser(Request $request): UserResource
    {
        return new UserResource($request->user()->load('company'));
    }

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'Logout Success'
        ]);
    }

    public function update(UserUpdateRequest $request): JsonResponse
    {
        $user = $request->user();

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('phoneNumber')) {
            $user->phone_number = $request->phoneNumber;
        }

        if ($request->has('address')) {
            $user->address = $request->address;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'data' => new UserResource($user->load('company'))
        ]);
    }
}

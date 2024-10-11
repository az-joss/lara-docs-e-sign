<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $loginRequest): JsonResponse
    {
        if (!Auth::attempt($loginRequest->validated())) {
            throw ValidationException::withMessages([
                'email' => [
                    'Incorrect credentials.'
                ],
            ]);
        }

        /**
         * @var \App\Models\User $user
         */
        $user = Auth::getUser();

        return response()->json([
            'access_token' => $user->createToken('access_token')->plainTextToken,
        ]);
    }

    public function logout(LogoutRequest $logoutRequest): JsonResponse
    {
        $logoutRequest->user()
            ->currentAccessToken()
            ->delete();

        return response()->json(status: 204);
    }

    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        $user = User::create($registerRequest->getData());

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('access_token')->plainTextToken,
        ]);
    }
}

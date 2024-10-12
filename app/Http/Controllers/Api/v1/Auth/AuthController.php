<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Api\v1\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Modules\OpenApi\Attributes as AppOAT;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OAT;

#[OAT\Tag(
    name: 'auth',
    description: 'Auth endpoints'
)]
class AuthController extends ApiController
{
    #[OAT\Post(
        path: '/auth/login',
        description: 'Authenticate user by credentials',
        tags: ['auth'],
        operationId: 'loginUser',
        requestBody: new OAT\RequestBody(
            content: new OAT\JsonContent(ref: '#components/schemas/LoginRequestBody'),
            required: true,
        ),
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Authentication successful',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'access_token',
                            type: 'string',
                            description: 'Access token'
                        )
                    ]
                ),
            ),
            new OAT\Response(
                response: 403,
                description: 'Invalid credentials',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'message',
                            type: 'string',
                            description: 'Common error message',
                            example: 'Incorrect credentials.',
                        )
                    ]
                ),
            ),
        ]
    )]
    public function login(LoginRequest $loginRequest): JsonResponse
    {
        if (!Auth::attempt($loginRequest->validated())) {
            throw new AuthorizationException('Incorrect credentials.');
        }

        /**
         * @var \App\Models\User $user
         */
        $user = Auth::getUser();

        return response()->json([
            'access_token' => $user->createToken('access_token')->plainTextToken,
        ]);
    }

    #[OAT\Post(
        path: '/auth/logout',
        description: 'Logout current user',
        tags: ['auth'],
        operationId: 'logoutUser',
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Logout current user'
            )
        ]
    )]
    public function logout(LogoutRequest $logoutRequest): JsonResponse
    {
        $logoutRequest->user()
            ->currentAccessToken()
            ->delete();

        return response()->json(status: 204);
    }

    #[OAT\Post(
        path: '/auth/register',
        description: 'Register new user',
        tags: ['auth'],
        operationId: 'registerUser',
        requestBody: new OAT\RequestBody(
            content: new OAT\JsonContent(ref: '#components/schemas/RegisterRequestBody'),
            required: true,
        ),
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Registration successful',
                content: new OAT\JsonContent(
                    properties: [
                        new OAT\Property(
                            property: 'access_token',
                            type: 'string',
                            description: 'Access token'
                        )
                    ]
                ),
            ),
            new AppOAT\InvalidDataResponse(),
        ],
    )]
    public function register(RegisterRequest $registerRequest): JsonResponse
    {
        $user = User::create($registerRequest->getData());

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('access_token')->plainTextToken,
        ]);
    }
}

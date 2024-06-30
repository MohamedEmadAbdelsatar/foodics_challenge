<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\UserService;

class AuthController extends Controller
{
    public function __construct(public UserService $userService)
    {
    }
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->userService->registerUser($request->all());

        return (new \App\Helpers\ApiResponse)->success(200, [
            'user' => $result['user']->only('id', 'name', 'email'),
            'access_token' => $result['token']
        ], 'User Registered successfully');
    }

    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->userService->LoginUser($request->all());

        return (new \App\Helpers\ApiResponse)->success(200, [
            'user' => $result['user']->only('id', 'name', 'email'),
            'access_token' => $result['token']
        ], 'User Logged-in successfully');
    }
}

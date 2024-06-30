<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class UserService
{
    public function __construct(public UserRepository $userRepository)
    {
    }
    public function registerUser(array $userAttributes): array
    {
        $user = $this->userRepository->storeUser($userAttributes);

        $token = $user->createToken('FoodicsBackend')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function LoginUser($loginCredentials): array
    {
        if (auth()->attempt($loginCredentials)) {
            return [
                'user' => auth()->user(),
                'token' => auth()->user()->createToken('FoodicsBackend')->plainTextToken
            ];
        }

        throw new HttpResponseException(
            response()->json(['message' => "Sorry! unable to login, Please make sure you are using right password."], Response::HTTP_BAD_REQUEST)
        );
    }
}

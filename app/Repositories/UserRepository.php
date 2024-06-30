<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function storeUser(array $userAttributes): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $userAttributes['password'] = Hash::make($userAttributes['password']);
        return User::query()->create($userAttributes);
    }
}

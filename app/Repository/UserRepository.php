<?php

namespace App\Repository;

use App\Models\User;
use App\Interfaces\UserRepoInterface;

class UserRepository implements UserRepoInterface
{
    public function insert(Array $attributes): ?User
    {
        $user = User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => bcrypt($attributes['password']),
        ]);

        return $user;
    }

    public function login(Array $attributes): ?string
    {
        $token = auth()->attempt($attributes);
        if (!$token) return null;
        return $token;
    }

    public function refresh(): ?string
    {
        return auth()->refresh();
    }

    public function logout(): bool
    {
        auth()->logout();
        return true;
    }
    
    public function getMe(): ?User
    {
        return auth()->user();
    }
}

<?php

namespace App\Services;

use App\Interfaces\UserRepoInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserService
{
    protected $UserRepository;

    public function __construct(UserRepoInterface $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    public function storeUser(Request $user): ?User
    {
        $validated = $user->validate([
            'name' => 'required|max:64',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);
        $data = $this->UserRepository->insert($validated);

        return $data;
    }

    public function logMeIn(Request $user): ?string
    {
        $validated = $user->validate([
            'email' => 'required',
            'password' => 'required|min:6',
        ]);
        $data = $this->UserRepository->login($validated);

        return $data;
    }

    public function refreshToken(): ?string
    {
        $data = $this->UserRepository->refresh();
        return $data;
    }

    public function logMeOut(): bool
    {
        $data = $this->UserRepository->logout();
        return $data;
    }

    public function getMyAccount(): ?User
    {
        return $this->UserRepository->getMe();
    }
}

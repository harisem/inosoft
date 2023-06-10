<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepoInterface
{
    function insert(Array $attributes): ?User;
    function login(Array $attributes): ?string;
    function refresh(): ?string;
    function logout(): bool;
    function getMe(): ?User;
}

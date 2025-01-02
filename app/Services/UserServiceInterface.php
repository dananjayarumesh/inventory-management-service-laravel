<?php

namespace App\Services;

use App\Models\User;

interface UserServiceInterface
{
    public function storeUser(string $name, string $email, string $role, string $password): void;
    public function updateUser(int $id, string $name, ?string $password): void;
}

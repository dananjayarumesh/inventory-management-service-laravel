<?php

namespace App\Services;

interface AuthServiceInterface
{
    public function getAccessTokenByCred(string $email, string $password): string;
}

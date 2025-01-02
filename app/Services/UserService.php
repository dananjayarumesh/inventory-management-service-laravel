<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function storeUser(string $name, string $email, string $role, string $password): void
    {
        $password = Hash::make($password);
        $this->userRepository->storeRecord(
            $name,
            $email,
            $role,
            $password
        );
    }

    public function updateUser(int $id, string $name, ?string $password): void
    {
        if ($password) {
            $password = Hash::make($password);
            $this->userRepository->updateRecord(
                $id,
                $name,
                $password
            );
        } else {
            $this->userRepository->updateRecordWithoutPassword(
                $id,
                $name
            );
        }
    }
}

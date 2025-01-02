<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getRecords(): array
    {
        return User::all()->toArray();
    }

    public function storeRecord(string $name, string $email, string $role, string $password): void
    {
        User::create(compact(
            'name',
            'email',
            'role',
            'password'
        ));
    }

    public function updateRecord(int $id, string $name, string $password): void
    {
        User::findOrFail($id)->update(compact(
            'name',
            'password'
        ));
    }

    public function updateRecordWithoutPassword(int $id, string $name): void
    {
        User::findOrFail($id)->update(compact(
            'name'
        ));
    }

    public function deleteRecord(int $id): void
    {
        User::destroy($id);
    }
}

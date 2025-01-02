<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function getRecords(): array;
    public function storeRecord(string $name, string $email, string $role, string $password): void;
    public function updateRecord(int $id, string $name, string $password): void;

    public function updateRecordWithoutPassword(int $id, string $name): void;

    public function deleteRecord(int $id): void;
}

<?php

namespace App\Repositories;

interface CategoryRepositoryInterface
{
    public function getRecords(): array;
    public function getSingleRecord(int $id): array;
    public function storeRecord(string $name): void;
    public function updateRecord(int $id, string $name): void;
}

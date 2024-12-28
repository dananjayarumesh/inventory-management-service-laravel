<?php 

namespace App\Repositories;

interface ItemRepositoryInterface {
    public function getPaginatedRecords(int $page, int $per_page): array; 
    public function getSingleRecord(int $id): array;
    public function storeRecord(string $name, int $catgoryId, int $createdBy): void;
}
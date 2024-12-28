<?php 

namespace App\Repositories;

interface ItemRepositoryInterface {
    public function getPaginatedRecords(int $page, int $per_page): array; 
    public function getSingleRecord(int $id): array;
}
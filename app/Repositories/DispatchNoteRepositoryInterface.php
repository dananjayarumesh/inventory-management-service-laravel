<?php 

namespace App\Repositories;

interface DispatchNoteRepositoryInterface {
    public function getPaginatedRecords(int $page, int $perPage): array; 
    public function getSingleRecord(int $id): array;
    public function storeRecordWithItemUpdate(int $itemId, string $note, int $qty, int $createdBy): void;
}
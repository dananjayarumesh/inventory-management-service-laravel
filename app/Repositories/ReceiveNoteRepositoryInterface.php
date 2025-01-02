<?php 

namespace App\Repositories;

interface ReceiveNoteRepositoryInterface {
    public function getRecords(): array; 
    public function getSingleRecord(int $id): array;
    public function storeRecordWithItemUpdate(int $itemId, string $note, int $qty, int $createdBy): void;
}
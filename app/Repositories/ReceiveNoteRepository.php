<?php

namespace App\Repositories;

use App\Models\Item;
use App\Models\ReceiveNote;
use Illuminate\Support\Facades\DB;

class ReceiveNoteRepository implements ReceiveNoteRepositoryInterface
{
    public function getRecords(): array
    {
        return ReceiveNote::all()->toArray();
    }

    public function getSingleRecord(int $id): array
    {
        return ReceiveNote::findOrFail($id)->toArray();
    }

    public function storeRecordWithItemUpdate(int $itemId, string $note, int $qty, int $createdBy): void
    {
        DB::transaction(function () use ($note, $itemId, $qty, $createdBy) {
            ReceiveNote::create([
                'note' => $note,
                'item_id' => $itemId,
                'qty' => $qty,
                'created_by' => $createdBy
            ]);
            
            $item = Item::findOrFail($itemId);
            $item->increment('qty', $qty);
        });
    }
}

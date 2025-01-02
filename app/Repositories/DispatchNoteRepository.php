<?php

namespace App\Repositories;

use App\Exceptions\ItemQtyNotSufficientException;
use App\Models\Category;
use App\Models\DispatchNote;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class DispatchNoteRepository implements DispatchNoteRepositoryInterface
{
    public function getPaginatedRecords(int $page, int $perPage): array
    {
        $skipCount = $page * $perPage - $perPage;
        return DispatchNote::skip($skipCount)
            ->take($perPage)
            ->get()
            ->toArray();
    }

    public function getSingleRecord(int $id): array
    {
        return DispatchNote::findOrFail($id)->toArray();
    }

    public function storeRecordWithItemUpdate(int $itemId, string $note, int $qty, int $createdBy): void
    {
        DB::transaction(function () use ($note, $itemId, $qty, $createdBy) {
            DispatchNote::create([
                'note' => $note,
                'item_id' => $itemId,
                'qty' => $qty,
                'created_by' => $createdBy
            ]);

            $item = Item::findOrFail($itemId);

            if ($item->qty < $qty) {
                throw new ItemQtyNotSufficientException('Item qty is not enough to process dispatch note.');
            }

            $item->decrement('qty', $qty);
        });
    }
}

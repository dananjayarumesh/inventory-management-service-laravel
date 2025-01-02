<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Item;
use App\Repositories\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getRecords(): array
    {
        return Category::all()->toArray();
    }

    public function getSingleRecord(int $id): array
    {
        return Category::findOrFail($id)->toArray();
    }

    public function storeRecord(string $name): void
    {
        Category::create([
            'name' => $name
        ]);
    }

    public function updateRecord(int $id, string $name): void
    {
        Category::findOrFail($id)->update([
            'name' => $name
        ]);
    }
}

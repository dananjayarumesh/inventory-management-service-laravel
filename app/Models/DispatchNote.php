<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'note',
        'item_id',
        'qty',
        'created_by'
    ];
}

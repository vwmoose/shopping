<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShoppingListItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'quantity',
        'is_picked',
        'orders'
    ];

    protected $casts = [
        'is_picked' => 'boolean'
    ];

    public function list(): BelongsTo
    {
        return $this->belongsTo(ShoppingList::class);
    }
}

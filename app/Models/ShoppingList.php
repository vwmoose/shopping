<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShoppingList extends Model
{
    use HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'spending_limit'
    ];

    /**
     * identify the shopping list items associated with this shopping list
     */
    public function items(): HasMany
    {
        return $this->hasMany(ShoppingListItem::class);
    }

    /**
     * filter shopping list by user id
     * 
     * @param String $id
     * 
     * @return Builder
     */
    public function scopeByUserId($query, $id): Builder
    {
        return $query->where('user_id', $id);
    }

    /**
     * Get spending limit formatted correctly to two decimal places.
     */
    protected function spendingLimit(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !is_null($value) ? number_format($value, 2) : null,
        );
    }
}

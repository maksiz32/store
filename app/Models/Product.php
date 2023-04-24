<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'store_id');
    }

    public function categoriesProducts()
    {
        return $this->hasMany(CategoriesProducts::class, 'product_id', 'id');
    }

    public function categories()
    {
//        return
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => $value / 100,
            set: fn (int $value) => $value * 100,
        );
    }
}

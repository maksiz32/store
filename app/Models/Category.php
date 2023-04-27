<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'store_id',
        'is_show_nav'
    ];

    public function clients(): BelongsTo
    {
        return $this->belongsTo(User::class, 'store_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'categories_products', 'category_id', 'product_id');
    }

    protected function isShowNav(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => (bool) $value,
        );
    }
}

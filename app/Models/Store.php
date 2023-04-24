<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name_store',
        'description',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'store_id', 'id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'store_id', 'store_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'store_id', 'store_id');
    }
}

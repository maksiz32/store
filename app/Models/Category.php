<?php

namespace App\Models;

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
}

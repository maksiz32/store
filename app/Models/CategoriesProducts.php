<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesProducts extends Model
{
    use HasFactory;

    public function products()
    {
        $this->belongsTo(Product::class, 'id', 'product_id');
    }
}

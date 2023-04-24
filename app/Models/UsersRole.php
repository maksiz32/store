<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UsersRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'description'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'users_role_id');
    }
}

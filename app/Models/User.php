<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'users_role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function users_roles(): BelongsTo
    {
        return $this->belongsTo(UsersRole::class, 'users_role_id', 'id');
    }

    public function store(): HasOne
    {
        return $this->hasOne(Store::class, 'store_id', 'id');
    }

    public function storeProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'store_id', 'id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'store_id', 'id');
    }

    public function categoriesThroughProducts(): HasOneThrough|HasManyThrough
    {
        return $this->hasManyThrough(
            CategoriesProducts::class,
            Product::class,
            'store_id',
            'product_id',
            'id',
            'id',
        )->orderBy('category_id');
    }
}

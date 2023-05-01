<?php

use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Stores\StoresController;
use App\Http\Controllers\Users\UsersController;
use Illuminate\Support\Facades\Route;

// Customers side
Route::middleware(['auth', 'isCustomer'])->group(function () {
    Route::get('/ajax/{client_id}', [StoresController::class, 'show'])->name('ajax-store.show');
});

require __DIR__.'/auth.php';

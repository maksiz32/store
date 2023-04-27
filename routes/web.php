<?php

use App\Http\Controllers\Categories\CategoryController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Stores\StoresController;
use App\Http\Controllers\Users\UsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Route::get('/', function () {
//    if (Auth::id()) {
//        return Inertia::render('Welcome', [
//            'canLogin' => Route::has('login'),
//            'canRegister' => Route::has('register'),
//        ]);
//    } else {
//        return Inertia::render('Auth/Login');
//    }
//});

Route::middleware('isAdmin')->group(function () {
    Route::get('/admin-panel', [UsersController::class, 'index']);
    Route::get('/users', [UsersController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [UsersController::class, 'createAdmin'])->name('admin.create');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('user.delete');

    Route::get('/stores', [StoresController::class, 'index'])->name('admin.stores');
});

//Route::get('/', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // This route make redirect to user store
    Route::get('/store', [StoresController::class, 'chooseStore'])->name('store');
    Route::get('/store/{client_id}', [StoresController::class, 'show'])->name('store.show');

    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.add');
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('category.delete');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('category.update');

    Route::post('/product', [ProductsController::class, 'store'])->name('product.add');
    Route::put('/product/{product}', [ProductsController::class, 'update'])->name('product.update');
    Route::post('/product/{product}', [ProductsController::class, 'imageDestroy'])->name('product.image-destroy');
    Route::delete('/product/{product}', [ProductsController::class, 'destroy'])->name('product.delete');
    Route::get('/store/{client_id}/{category_id?}/{product_id}', [ProductsController::class, 'show'])
        ->name('product.show');
});

require __DIR__.'/auth.php';

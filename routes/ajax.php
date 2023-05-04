<?php

use App\Http\Controllers\Stores\BasketsController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Stores\StoresController;
use Illuminate\Support\Facades\Route;

// Customers side
Route::middleware(['auth', 'isCustomer'])->group(function () {
    Route::get('/ajax/store/{client_id}/{category_id}', [ProductsController::class, 'getProductsByCategory'])
        ->name('ajax-store.show');

    Route::get('/ajax/store-id/{product_id}', [StoresController::class, 'getStoreIdByProduct'])
        ->name('ajax-store.id');

    Route::post('/ajax/basket', [BasketsController::class, 'store'])
        ->name('ajax-basket.add');
    Route::get('/ajax/basket/{customer_id}', [BasketsController::class, 'getBasketByUser'])
        ->name('ajax-basket.main');
    Route::get('/ajax/basket/erase/{id}', [BasketsController::class, 'clearUserBasket'])
        ->name('ajax-basket.erase');
});

require __DIR__.'/auth.php';

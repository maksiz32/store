<?php

namespace App\Http\Controllers\Stores;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class StoresController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Stores/AllStores', [
            'stores' => Store::with(['client', 'products'])->get(),
        ]);
    }

    public function chooseStore(): RedirectResponse
    {
        $store = Store::where('store_id', Auth::id())->first();

        return redirect()->route('store.show', ['client_id' => $store->store_id]);
    }

    public function show(int $id)
    {
        /** @var Store $store */
        $store = Store::with(['products', 'categories'])->where('store_id', $id)->first();
        $products = $store->products()->has('categoriesProducts')->get();

        return Inertia::render('Stores/Store', [
            'store' => $store,
            'products' => $products,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Stores;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class StoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('isOwner')->except(['index', 'chooseStore']);
    }

    public function index(): Response
    {
        return Inertia::render('Admin/CRMStores/AllStores', [
            'stores' => Store::with(['client', 'products'])->get(),
        ]);
    }

    public function chooseStore(): RedirectResponse
    {
        $store = Store::where('store_id', Auth::id())->first();
        if (!$store) {
            return redirect()->route('dashboard');
        }

        return redirect()->route('store.show', ['client_id' => $store->store_id]);
    }

    public function show(int $id): Response|RedirectResponse
    {
        /** @var Store $store */
        $store = Store::with('categories')->where('store_id', $id)->first();
        if (!$store) {
            return redirect()->route('dashboard');
        }

        $products = $store->products()
            ->with('categories')
            ->orderBy('updated_at', 'desc')
            ->get();

        return Inertia::render('Clients/CRMStores/Store', [
            'store' => $store,
            'products' => $products,
            'categories' => Category::where('store_id', $store->store_id)->get(),
        ]);
    }
}

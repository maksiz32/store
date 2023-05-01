<?php

namespace App\Http\Controllers\Stores;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use App\Models\UsersRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class StoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('isOwner')->only(['show']);
    }

    public function index(): Response
    {
        return Inertia::render('CRM/Admins/AllStores', [
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
        $store = Store::with(['categories', 'client'])->where('store_id', $id)->first();
        if (!$store) {
            return redirect()->route('dashboard');
        }
        $userRoleName = UsersRole::where('id', $store->client->users_role_id)->first();
        $store->client['users_roles'] = $userRoleName;

        $products = $store->products()
            ->with(['categories' => function ($query) {
                    $query->orderBy('name', 'desc');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('CRM/Clients/Store', [
            'store' => $store,
            'products' => $products,
            'categories' => Category::where('store_id', $store->store_id)->get(),
        ]);
    }

    public function updateActive(Store $store): RedirectResponse
    {
        $store->update(['is_active' => !(bool) $store->is_active]);

        return back();
    }

    public function destroy(Store $store): RedirectResponse
    {
        // If need also to delete store owner
//        if ($store->client()) {
//            $store->client()->delete();
//        }

        $store->delete();

        return back();
    }

    public function clearStore(Store $store): RedirectResponse
    {
        foreach ($store->products as $product) {
            $product->categories()->detach();
        }
        $store->products()->delete();
        $store->categories()->delete();

        return back();
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|integer|exists:stores,store_id',
            'name_store' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:255',
        ]);

        $store = Store::where('store_id', $validated['store_id'])->first();
        unset($validated['store_id']);
        $store->update($validated);

        return back();
    }
}

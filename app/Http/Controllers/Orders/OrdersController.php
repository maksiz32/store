<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Stores\BasketsController;
use App\Models\Basket;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrdersController extends Controller
{
    public function index()
    {
        /** @var Basket $basket */
        $basket = Basket::query()->where('user_id', Auth::id())->first();

        return Inertia::render('Shops/OrderForm', [
            'basket' => json_decode($basket->basket),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|numeric|exists:users,id',
            'address' => 'required|string|max:1000',
            'phone' => 'required|string|max:20',
            'order' => 'required|array',
            'description' => 'required|string|max:255',
            'totalPrice' => 'required|numeric',
        ]);
        $validated['order'] = json_encode($validated['order']);
        $successOrder = Order::create($validated);

        if ($successOrder) {
            $basket = Basket::where('user_id', $validated['customer_id'])->first();
            BasketsController::clearUserBasket($basket->id);
        }

        return Inertia::render('Shops/OrderSuccess');
    }
}

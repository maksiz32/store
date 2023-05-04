<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Resources\OrdersResourceController;
use App\Http\Controllers\Stores\BasketsController;
use App\Models\Basket;
use App\Models\Order;
use App\Models\OrdersDescription;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrdersController extends Controller
{
    public function index()
    {
        /** @var Basket $basket */
        $basket = Basket::query()->where('user_id', Auth::id())->first();
        $response = $basket ? json_decode($basket->basket) : [];

        return Inertia::render('Shops/OrderForm', [
            'basket' => $response,
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
        ]);
        $description = OrdersDescription::create($validated);

        $storesOrders = OrdersResourceController::makeOrderByStoreIds($validated['order']);
        $order = false;
        foreach ($storesOrders as $key => $order) {
            Order::create([
                'orders_descriptions_id' => $description->id,
                'store_id' => $key,
                'order' => $order['orders'],
                'total_price' => $order['total_price'],
            ]);
            $order = true;
        }

        if ($order) {
            $basket = Basket::where('user_id', $validated['customer_id'])->first();
            BasketsController::clearUserBasket($basket->id);
        }

        return Inertia::render('Shops/OrderSuccess');
    }

    public function showOrdersListByStores()
    {
        $store = Store::with(['orders' => function ($query) {
                    $query->with(['ordersDescription' => function ($query) {
                        $query->with(['customer']);
                    }
                ]);
            }
        ])->where('store_id', Auth::id())->first();

        return Inertia::render('CRM/Clients/OrdersList', [
            'shop' => $store,
        ]);
    }

    public function show(int $id)
    {
        $order = Order::with(['ordersDescription' => function ($query) {
                        $query->with(['customer']);
                    }])
            ->where('id', $id)->first();

        return Inertia::render('CRM/Clients/Order', [
            'order' => $order,
        ]);
    }

    public function paid(int $orderId)
    {
        $order = Order::query()->find($orderId);
        $order->update(['is_payed' => !(bool) $order->is_payed]);

        return back();
    }
}

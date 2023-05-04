<?php

namespace App\Http\Controllers\Orders;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrdersController extends Controller
{
    public function index()
    {
        return Inertia::render('Shops/OrderForm');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => '',
            'store_id' => '',
            'address' => '',
            'phone' => '',
            'order' => '',
            'description' => '',
            'totalPrice' => '',
        ]);
    }
}

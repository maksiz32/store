<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $price = null;
        $categoryId = null;

        dd($request);die();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'string',
            'price' => 'numeric',
            'quantity' => 'numeric',
            'description' => 'string',
            'store_id' => 'required|integer|exists:stores,store_id',
            'category_id' => 'numeric',
        ]);
        if ($validated['category_id']) {
            $categoryId = (int) $validated['category_id'];
        }
        if ((int) $validated['price']) {
            $price = ((float) $validated['price']) * 100;
        }
        $validated['quantity'] = (int) $validated['quantity'];
        // Транзакция, запись в соединительной таблице

        return response(['message' => 'Good']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}

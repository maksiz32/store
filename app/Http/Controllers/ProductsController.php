<?php

namespace App\Http\Controllers;

use App\Models\CategoriesProducts;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'string|nullable',
            'price' => 'numeric|gte:0',
            'quantity' => 'integer|gte:0',
            'description' => 'string|nullable',
            'store_id' => 'required|integer|exists:stores,store_id',
            'category_id' => 'array|nullable',
        ]);
        if ((int) $validated['price']) {
            $price = ((float) $validated['price']);
        }
        $validated['quantity'] = (int) $validated['quantity'];

        /** @var Product $product */
        $product = Product::create([
            'name' => $validated['name'],
            'image' => $validated['image'],
            'price' => $price,
            'quantity' => $validated['quantity'],
            'description' => $validated['description'],
            'store_id' => $validated['store_id'],
        ]);

        $categories = Category::query()->whereIn('name', $validated['category_id'])->get();

        foreach ($categories as $category) {
            $product->categories()->attach($category->id);
        }

        return redirect()->route('store');
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'sometimes|string|nullable',
            'price' => 'numeric|gte:0',
            'quantity' => 'integer|gte:0',
            'description' => 'string|nullable',
            'category_id' => 'array|nullable',
        ]);

        $product->update($validated);

        // Надо удалить те связи с категориями, которых нет в $validated['category_id']
        CategoriesProducts::where('product_id', $product->id)->delete();

        if ($validated['category_id']) {
            $categories = Category::query()->whereIn('name', $validated['category_id'])->get();

            foreach ($categories as $category) {
                $product->categories()->attach($category->id);
            }
        }

        return back();
    }

    public function imageDestroy(Product $product)
    {
        $product->update([
            'image' => null,
         ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $pivots = CategoriesProducts::where('product_id', $product->id)->get();
        foreach ($pivots as $pivot) {
            $pivot->delete();
        }
        $product->delete();

        return redirect()->route('store');
    }
}

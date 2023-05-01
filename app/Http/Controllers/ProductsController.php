<?php

namespace App\Http\Controllers;

use App\Models\CategoriesProducts;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
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
    public function store(Request $request): RedirectResponse
    {
        $price = null;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'string|nullable',
            'price' => 'numeric|gte:0',
            'quantity' => 'integer|gte:0',
            'description' => 'string|nullable',
            'store_id' => 'required|integer|exists:stores,store_id',
            'categories' => 'array|nullable',
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

        if ($validated['categories']) {
            $categoriesNames = array_column($validated['categories'], 'name');
            $categories = Category::query()->whereIn('name', $categoriesNames)->get();

            foreach ($categories as $category) {
                $product->categories()->attach($category->id);
            }
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
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
            'categories' => 'array|nullable',
        ]);

        $product->update($validated);

        // Надо удалить те связи с категориями, которых нет в $validated['category_id']
        CategoriesProducts::where('product_id', $product->id)->delete();

        if ($validated['categories']) {
            $categoriesNames = array_column($validated['categories'], 'name');
            $categories = Category::query()->whereIn('name', $categoriesNames)->get();

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

        return back();
    }
}

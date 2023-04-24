<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'store_id' => 'required|integer|exists:stores,store_id',
            'is_show_nav' => 'required|integer',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'store_id' => $validated['store_id'],
            'is_show_nav' => $validated['is_show_nav'],
        ]);

        return back();
    }
}

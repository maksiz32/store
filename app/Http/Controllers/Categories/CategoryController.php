<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\CategoriesProducts;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('CRM/Clients/Categories', [
            'categories' => Category::where('store_id', Auth::id())->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'store_id' => 'required|integer|exists:stores,store_id',
            'is_show_nav' => 'required|boolean',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'store_id' => $validated['store_id'],
            'is_show_nav' => $validated['is_show_nav'],
        ]);

        return back();
    }

    public function edit(Category $category)
    {
        return Inertia::render('Clients/CRMStores/EditCategory', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string',
            'is_show_nav' => 'required|boolean',
        ]);
        $category->update($validated);

        return back();
    }

    public function destroy(Category $category): RedirectResponse
    {
        $pivots = CategoriesProducts::where('category_id', $category->id)->get();
        foreach ($pivots as $pivot) {
            $pivot->delete();
        }
        $category->delete();

        return redirect()->route('categories');
    }
}

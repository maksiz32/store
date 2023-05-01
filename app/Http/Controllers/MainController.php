<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MainController extends Controller
{
    public function index()
    {
        return Inertia::render('Shops/Main', [
            'shops' => Store::where('is_active', true)->orderBy('store_id', 'desc')->get(),
        ]);
    }
}

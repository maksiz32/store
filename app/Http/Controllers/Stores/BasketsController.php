<?php

namespace App\Http\Controllers\Stores;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use Illuminate\Http\Request;

use function PHPUnit\Framework\returnArgument;

class BasketsController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|numeric|exists:users,id',
            'basket' => 'array|nullable',
        ]);
        $basket = $validated['basket'] ?: [];

        Basket::query()->updateOrCreate(
            ['user_id' => (int) $validated['user_id']],
            ['basket' => json_encode($basket)]
        );

        return response('Ok');
    }

    public static function clearUserBasket($id) {
        $id = (int) $id;
        $basket = Basket::find($id);
        $basket->delete();

        return response('Ok');
    }

    public function getBasketByUser(int $customerId)
    {
        $basket = Basket::where('user_id', $customerId)->first();
        $response = $basket?->basket;

        return response()->json($response);
    }
}

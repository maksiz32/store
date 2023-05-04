<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrdersResourceController extends Controller
{
    public static function makeOrderByStoreIds(array $order)
    {
        $arrayIntersect[$order[0]['store_id']] = [
            'orders' => [$order[0]],
            'total_price' => (int) $order[0]['price'] * (int) $order[0]['count'],
        ];
        $countArray = count($order);
        for ($index = 1; $index < $countArray; $index++) {
            if (array_key_exists($order[$index]['store_id'], $arrayIntersect)) {
                $arrayIntersect[$order[$index]['store_id']]['orders'][] = $order[$index];
                $arrayIntersect[$order[$index]['store_id']]['total_price'] += $order[$index]['price'] * $order[$index]['count'];
            } else {
                $arrayIntersect[$order[$index]['store_id']] = [
                    'orders' => [$order[$index]],
                    'total_price' => $order[$index]['price'] * $order[$index]['count'],
                ];
            }
        }

        return $arrayIntersect;
    }
}

<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class FakeMarket
{
    public static function farmers_market () : JsonResponse
    {
        $res = [
            'quantitySold' => rand(0,10),
        ];

        return response()->json($res, 200);
    }
}

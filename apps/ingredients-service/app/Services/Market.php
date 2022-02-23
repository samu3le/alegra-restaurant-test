<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Market
{
    public static function Buy(string $ingredient) : array
    {
        try {
            $response = Http::acceptJson()->get('https://recruitment.alegra.com/api/farmers-market/buy', [
                'ingredient' => $ingredient,
            ]);
            if($response->successful()){
                return $response->json();
            }elseif($response->failed()){
                return [
                    'quantitySold' => 0,
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error: '.$e->getMessage(),
            ];
        }

    }
}
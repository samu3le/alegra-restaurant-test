<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        $ingredients = [
            'tomato',
            'lemon',
            'potato',
            'rice',
            'ketchup',
            'lettuce',
            'onion',
            'cheese',
            'meat',
            'chicken',
        ];
        $ingredients_db = Ingredient::whereIn('key',$ingredients)->pluck('key')->toArray();
        foreach($ingredients_db as $ingredient) {
            $index = array_search($ingredient,$ingredients);
            unset($ingredients[$index]);
        }
        if(count($ingredients) > 0) {
            foreach($ingredients as $ingredient) {
                Ingredient::create([
                    'name' => $ingredient,
                    'key' => $ingredient,
                    'is_active' => true,
                    'stock' => 5,
                    'image' => null,
                ]);
            }
        }else{
            \Log::info('No ingredients to seed');
        }
    }
}

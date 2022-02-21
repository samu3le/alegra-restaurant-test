<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Product;
use App\Models\Ingredient;
use App\Models\Recipe;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products_ids = Product::all()->pluck('id')->toArray();
        $ingredients_ids = Ingredient::all()->pluck('id')->toArray();
        $recipes = [];
        foreach($products_ids as $product_id) {
            $quantity_random = random_int(
                1,
                count($ingredients_ids),
            );
            $quantity_random = $quantity_random > 1 ? $quantity_random : 2;
            $ingredients_ids_randoms = array_rand(
                $ingredients_ids,
                $quantity_random,
            );
            foreach($ingredients_ids_randoms as $index) {
                $recipes[] = [
                    'product_id' => $product_id,
                    'ingredient_id' => $ingredients_ids[$index],
                    'quantity' => rand(1,10),
                ];
            }
        }
        foreach($recipes as $recipe) {
            Recipe::create($recipe);
        }
    }
}

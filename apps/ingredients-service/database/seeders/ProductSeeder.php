<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            'pizza',
            'burger',
            'chicken',
            'salad',
            'soup',
            'coffee',
            'tea',
            'beer',
            'wine',
            'milk',
            'juice',
            'water',
            'coke',
            'pepsi',
            'fanta',
            'sprite',
            'coca-cola',
            'arepa',
            'tacos',
            'sandwich',
            'chips',
            'chocolate',
            'ice-cream',
            'cake',
            'cookies',
            'pancakes',
            'chile',
            'salsa',
            'sauce',
            'sushi',
            'salmon',
            'steak',
            'chicken-wings',
            'chicken-wings-with-rice',
            'chicken-wings-with-rice-and-sauce',
            'chicken-wings-with-rice-and-sauce-and-chili',
            'chicken-wings-with-rice-and-sauce-and-chili-and-sauce',
        ];
        $products_db = Product::whereIn('name',$products)->pluck('name')->toArray();
        foreach($products_db as $product) {
            $index = array_search($product,$products_db);
            unset($products[$index]);
        }
        if(count($products) > 0) {
            foreach($products as $product) {
                Product::create([
                    'name' => $product,
                ]);
            }
        }else{
            $env = config('app.env');
            if($env !== 'production'){
                foreach(range(1,5) as $index) {
                    $random = $index . '-' . time() . bin2hex(random_bytes(10));
                    foreach($products_db as $product) {
                        Product::factory([
                            'name' => $product . '-' . $random,
                        ])->create();
                    }
                }
            }
        }
    }
}

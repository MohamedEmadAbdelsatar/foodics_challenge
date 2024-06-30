<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $beef    = Ingredient::query()->where('name', 'Beef')->first();
        $cheese  = Ingredient::query()->where('name', 'Cheese')->first();
        $onion   = Ingredient::query()->where('name', 'Onion')->first();

        $burger = Product::query()->updateOrCreate(['name' => 'Burger']);
        $burger->ingredients()->sync([
            $beef->id   => ['quantity' => 150],
            $cheese->id => ['quantity' => 30],
            $onion->id  => ['quantity' => 20]
        ]);
    }
}

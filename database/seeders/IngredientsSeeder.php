<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Beef', 'full_stock'   => 20000, 'current_stock' => 20000, 'merchant_id' => 2, 'unit_id' => 1],
            ['name' => 'Cheese', 'full_stock' => 5000, 'current_stock'  => 5000, 'merchant_id' => 1, 'unit_id' => 1],
            ['name' => 'Onion', 'full_stock'  => 1000, 'current_stock'  => 1000, 'merchant_id' => 1, 'unit_id' => 1]
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::query()->updateOrCreate(['name' => $ingredient['name']], $ingredient);
        }
    }

}

<?php

namespace Database\Factories;

use App\Models\Merchant;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $stock = fake()->numberBetween(10000, 20000);
        return [
            'name' => fake()->word(),
            'full_stock' => $stock ,
            'current_stock' => $stock,
            'merchant_id' => Merchant::factory()->create(),
            'is_merchant_notified' => false,
            'unit_id' => Unit::factory()->create(),
        ];
    }
}

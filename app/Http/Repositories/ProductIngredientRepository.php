<?php

namespace App\Http\Repositories;

use App\Models\Product;

class ProductIngredientRepository
{
    public function findProduct($productId): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {
        return Product::query()->findOrFail($productId);
    }

    public function update($model, $column, $value)
    {
        $model->update([$column => $value]);
    }
    public function decrementIngredientStock($ingredient, $quantity): void
    {
        $ingredient->decrement('current_stock', $quantity);
    }
}

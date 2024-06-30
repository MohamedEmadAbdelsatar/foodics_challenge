<?php

namespace App\Observers;

use App\Http\Repositories\ProductIngredientRepository;
use App\Mail\NotifyMerchantLowStock;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Mail;

class IngredientObserver
{
    private ProductIngredientRepository $productIngredientRepository;
    public function __construct(ProductIngredientRepository $productIngredientRepository)
    {
        $this->productIngredientRepository = $productIngredientRepository;
    }
    public function updated(Ingredient $ingredient)
    {
        if ($ingredient->isDirty('current_stock') && $ingredient->current_stock < $ingredient->full_stock / 2 && !$ingredient->is_merchant_notified) {
            $ingredient->load('merchant', 'unit');
            Mail::to(['email' => $ingredient->merchant->email, 'name' => $ingredient->merchant->name])->send(new NotifyMerchantLowStock($ingredient));

            $this->productIngredientRepository->update($ingredient, 'is_merchant_notified', true);
        }
    }
}

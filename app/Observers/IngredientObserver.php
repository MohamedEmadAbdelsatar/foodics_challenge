<?php

namespace App\Observers;

use App\Mail\NotifyMerchantLowStock;
use App\Models\Ingredient;
use App\Repositories\ProductIngredientRepository;
use Illuminate\Support\Facades\Mail;

class IngredientObserver
{
    private ProductIngredientRepository $productIngredientRepository;
    public function __construct(ProductIngredientRepository $productIngredientRepository)
    {
        $this->productIngredientRepository = $productIngredientRepository;
    }
    public function updated(Ingredient $ingredient): void
    {
        if ($ingredient->isDirty('current_stock') && $ingredient->current_stock < ($ingredient->full_stock / 2) && !$ingredient->is_merchant_notified) {
            $ingredient->load('merchant', 'unit');
            Mail::to($ingredient->merchant->email)->send(new NotifyMerchantLowStock($ingredient));

            $this->productIngredientRepository->update($ingredient, 'is_merchant_notified', true);
        }
    }
}

<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\ProductIngredientRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class IngredientService
{
    public function __construct(
        protected ProductIngredientRepository $productIngredientRepository,
        protected OrderRepository $orderRepository,
    )
    {
    }
    public function updateStock(array $requestProducts, $order): void
    {
        foreach ($requestProducts as $requestProduct) {
            $product = $this->productIngredientRepository->findProduct($requestProduct['product_id']);
            $product->load('ingredients');

            foreach ($product->ingredients as $ingredient) {
                $totalOrderIngredientQuantity = $requestProduct['quantity'] * $ingredient->pivot->quantity;

                if ($ingredient->current_stock < $totalOrderIngredientQuantity) {
                    $this->orderRepository->updateOrder($order, 'status', Order::REJECTEDSTATUS);
                    throw new HttpResponseException(
                        response()->json(['message' => "Sorry! there is not enough stock {$ingredient->name}, We can\'t proceed with your Order"], Response::HTTP_BAD_REQUEST)
                    );
                }

                $this->productIngredientRepository->decrementIngredientStock($ingredient, $totalOrderIngredientQuantity);
            }
        }
    }
}

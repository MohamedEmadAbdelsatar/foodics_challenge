<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{


    public function __construct(
        protected OrderRepository $orderRepository,
        protected IngredientService $ingredientService
    ){
    }
    public function store(array $requestProducts)
    {
        return DB::transaction(function() use ($requestProducts) {
            $order = $this->orderRepository->create(auth()->id());
            $this->ingredientService->updateStock($requestProducts, $order);
            $order = $this->orderRepository->updateOrder($order, 'status', Order::CONFIRMEDSTATUS);
            $this->orderRepository->attach($order, $requestProducts);

            return $order->load('products', 'user');
        });
    }
}

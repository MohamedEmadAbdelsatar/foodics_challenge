<?php

namespace App\Http\Services;

use App\Http\Repositories\OrderRepository;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private OrderRepository $orderRepository;
    private IngredientService $ingredientService;

    public function __construct(
        OrderRepository $orderRepository,
        IngredientService $ingredientService
    ){
        $this->orderRepository = $orderRepository;
        $this->ingredientService = $ingredientService;
    }
    public function store(array $requestProducts)
    {
        return DB::transaction(function() use ($requestProducts) {
            $order = $this->orderRepository->create(1);
            $this->ingredientService->updateStock($requestProducts, $order);
            $this->orderRepository->updateOrder($order, 'status', Order::CONFIRMEDSTATUS);
            return $this->orderRepository->attach($order, $requestProducts);
        });
    }
}

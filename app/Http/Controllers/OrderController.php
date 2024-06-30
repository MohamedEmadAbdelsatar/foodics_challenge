<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService) {
    }
    public function store(OrderRequest $request): \Illuminate\Http\JsonResponse
    {
        $order = $this->orderService->store($request->products);

        return (new \App\Helpers\ApiResponse)->success(200, [
            'order' => OrderResource::make($order),
        ], 'Order created successfully');
    }
}

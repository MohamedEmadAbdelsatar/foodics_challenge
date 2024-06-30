<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService) {
    }
    public function store(OrderRequest $request)
    {
        $order = $this->orderService->store($request->products);

        return ApiResponse::success(200, [
            'apiItem' => OrderResource::make($order),
        ], 'Order created successfully');
    }
}

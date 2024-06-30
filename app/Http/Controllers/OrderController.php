<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(public OrderService $orderService) {
    }
    public function store(OrderRequest $request)
    {
        $order = $this->orderService->store($request->products);
    }
}

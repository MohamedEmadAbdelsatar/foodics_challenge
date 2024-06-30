<?php

namespace App\Http\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function create($userId): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        return Order::query()->create(['user_id' => $userId, 'status' => Order::PENDINGSTATUS]);
    }

    public function attach($order, array $products): \Illuminate\Database\Eloquent\Builder
    {
        $order->products()->attach($products);

        return $order->with('products');
    }

    public function updateOrder($order, $column, $value)
    {
        $order->query()->update([$column => $value]);
    }
}

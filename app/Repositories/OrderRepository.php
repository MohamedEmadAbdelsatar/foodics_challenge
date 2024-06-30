<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function create($userId): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        return Order::query()->create(['user_id' => $userId, 'status' => Order::PENDINGSTATUS]);
    }

    public function attach($order, array $products): void
    {
        $order->products()->attach($products);
    }

    public function updateOrder($order, $column, $value)
    {
        return $order->query()->updateOrCreate(['id' => $order->id], [$column => $value]);
    }
}

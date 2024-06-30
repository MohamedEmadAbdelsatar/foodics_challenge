<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'user_name' => $this->user ? $this->user->name : '',
            'user_email' => $this->user ? $this->user->email : '',
            'products' => count($this->products) ? $this->products->map(function ($product) {
                return [
                    'product_name' => $product->name,
                    'quantity' => $product->pivot->quantity,
                ];
            }) : [],
        ];
    }
}

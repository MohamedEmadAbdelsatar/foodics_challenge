<?php

namespace Tests\Feature;

use App\Mail\NotifyMerchantLowStock;
use App\Models\Ingredient;
use App\Models\Merchant;
use App\Models\Order;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_creating_order_successfully(): void
    {
        Mail::fake();
        $unit = Unit::factory()->create();
        $merchant = Merchant::factory()->create();
        $firstIngredient = Ingredient::factory()
            ->for($unit)
            ->for($merchant)
            ->create();
        $secondIngredient = Ingredient::factory()
            ->for($unit)
            ->for($merchant)
            ->create();
        $thirdIngredient = Ingredient::factory()
            ->for($unit)
            ->for($merchant)
            ->create();
        $firstProduct = Product::factory()
            ->hasAttached(
               $firstIngredient,
                ['quantity' => 150]
            )
            ->hasAttached(
                $secondIngredient,
                ['quantity' => 30]
            )
            ->hasAttached(
                $thirdIngredient,
                ['quantity' => 20]
            )->create();
        $secondProduct = Product::factory()
            ->hasAttached(
                $firstIngredient,
                ['quantity' => 200]
            )
            ->hasAttached(
                $secondIngredient,
                ['quantity' => 40]
            )
            ->create();

        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $requestData = [
            'products' => [
                [
                    'product_id' => $firstProduct->id,
                    'quantity' => rand(1, 5)
                ], [
                    'product_id' => $secondProduct->id,
                    'quantity' => rand(1, 5)
                ]
            ]
        ];

        $response = $this->post('api/orders', $requestData);
        $response->assertStatus(200);
        $order = $response->json('data.order');
        $this->assertNotNull($order);
        $this->assertEquals($user->name, $order['user_name']);
        $products = $order['products'];
        $this->assertCount(2, $products);
        $this->assertEquals($firstProduct->name, $products[0]['product_name']);
        $this->assertEquals($secondProduct->name, $products[1]['product_name']);

        $orderIngredients = [];
        $DBOrder = Order::query()->first()->load('products.ingredients');
        foreach ($DBOrder->products as $orderProduct) {
            foreach ($orderProduct->ingredients as $ingredient) {
                $orderIngredients[$ingredient->id] = array_key_exists($ingredient->id, $orderIngredients) ? $orderIngredients[$ingredient->id] + ($ingredient->pivot->quantity * $orderProduct->pivot->quantity) :
                    $ingredient->pivot->quantity * $orderProduct->pivot->quantity;
            }
        }

        foreach ($orderIngredients as $id => $amount) {
            $this->assertDatabaseHas('ingredients', [
                'id' => $id,
                'current_stock' => (Ingredient::query()->find($id)->full_stock) - $amount
            ]);
        }
        Mail::assertNothingQueued();
    }

    public function test_not_enough_ingredient_stock()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');
        $unit = Unit::factory()->create();
        $merchant = Merchant::factory()->create();
        $firstIngredient = Ingredient::factory()
            ->set('current_stock', 0)
            ->for($unit)
            ->for($merchant)
            ->create();
        $firstProduct = Product::factory()
            ->hasAttached(
                $firstIngredient,
                ['quantity' => 150]
            )
            ->create();

        $requestData = [
            'products' => [
                [
                    'product_id' => $firstProduct->id,
                    'quantity' => rand(1, 5)
                ]
            ]
        ];
        $response = $this->post('api/orders', $requestData);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment(['message' => "Sorry! there is not enough stock {$firstIngredient->name}, We can\'t proceed with your Order"]);
    }

    public function test_if_user_entered_wrong_quantity()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');
        $unit = Unit::factory()->create();
        $merchant = Merchant::factory()->create();
        $firstIngredient = Ingredient::factory()
            ->for($unit)
            ->for($merchant)
            ->create();
        $firstProduct = Product::factory()
            ->hasAttached(
                $firstIngredient,
                ['quantity' => 150]
            )
            ->create();

        $requestData = [
            'products' => [
                [
                    'product_id' => $firstProduct->id,
                    'quantity' => 0
                ]
            ]
        ];
        $response = $this->post('api/orders', $requestData);
        $response->assertJsonValidationErrors('products.0.quantity');
    }

    public function test_sending_email_if_stock_is_less_than_half_of_full_stock()
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->actingAs($user, 'web');
        $unit = Unit::factory()->create();
        $merchant = Merchant::factory()->create();
        $firstIngredient = Ingredient::factory()
            ->set('current_stock', 250)
            ->set('full_stock', 400)
            ->for($unit)
            ->for($merchant)
            ->create();
        $firstProduct = Product::factory()
            ->hasAttached(
                $firstIngredient,
                ['quantity' => 150]
            )
            ->create();

        $requestData = [
            'products' => [
                [
                    'product_id' => $firstProduct->id,
                    'quantity' => 1
                ]
            ]
        ];
        $response = $this->post('api/orders', $requestData);
        $response->assertStatus(200);
        $order = $response->json('data.order');
        $this->assertNotNull($order);
        $this->assertEquals($user->name, $order['user_name']);
        $products = $order['products'];
        $this->assertCount(1, $products);
        $this->assertEquals($firstProduct->name, $products[0]['product_name']);

        Mail::to($firstIngredient->merchant->email)->send(new NotifyMerchantLowStock($firstIngredient));
        Mail::assertSent(NotifyMerchantLowStock::class);

        $this->assertDatabaseHas('ingredients', [
            'id' => $firstIngredient->id,
            'is_merchant_notified' => true
        ]);
    }
}

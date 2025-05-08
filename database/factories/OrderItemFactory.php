<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ShopOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shop_order_id' => ShopOrder::inRandomOrder()->first()->id,
            'product_id' => Product::inRandomOrder()->first()->id
        ];
    }
}

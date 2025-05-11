<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShopOrder>
 */
class ShopOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $wholesalePrice = $this->faker->randomFloat(2, 10, 1000);
        $count = $this->faker->numberBetween(1, 5);

        return [
            'shop_id' => Shop::inRandomOrder()->first()->id,
            'client_id' => Client::inRandomOrder()->first()->id,
            'product_id' => Product::inRandomOrder()->first()->id,
            'wholesale_price' => $this->faker->randomFloat(2, 10, 1000),
            'selling_price' => $wholesalePrice,
            'count' => $count,
            'total_price' => $wholesalePrice * $count,
            'status' => $this->faker->randomElement(['checking', 'reviewing', 'delivering', 'canceled']),
            'customer_note' => $this->faker->sentence,
        ];
    }

    public function checking(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'checking',
        ]);
    }

    public function delivering(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'delivering',
        ]);
    }

}

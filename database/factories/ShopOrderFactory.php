<?php

namespace Database\Factories;

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
        return [
            'shop_id' => Shop::inRandomOrder()->first()->id,
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'total_price' => $this->faker->randomFloat(2, 10, 1000),
            'status' => $this->faker->randomElement(['checking', 'reviewing', 'delivering', 'canceled']),
            'customer_note' => $this->faker->sentence,
        ];
    }

    /*  public function checking(): static
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
      }*/

    // Usage:
    //$deliveringOrder = \App\Models\Order::factory()->delivering()->create();
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'details' => $this->faker->paragraphs(3, true),
            'type' => $this->faker->randomElement(['Electronics', 'Clothing', 'Food', 'Furniture', 'Other']),
            'image' => 'product/image.jpg',
            'wholesale_price' => $this->faker->randomFloat(2, 10, 500),
            'selling_price' => $this->faker->randomFloat(2, 15, 750),
            'is_available' => $this->faker->boolean(80),
        ];
    }
}

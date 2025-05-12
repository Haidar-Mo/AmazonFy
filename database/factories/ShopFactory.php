<?php

namespace Database\Factories;

use App\Models\ShopType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'user_id' => User::factory(),
            'name' => $this->faker->name,
            'phone_number' => $this->faker->phoneNumber(),
            'identity_number' => $this->faker->randomNumber(9),
            'logo' => 'logo/image.jpg',
            'identity_front_face' => 'identity/image.jpg',
            'identity_back_face' => 'identity/image.jpg',
            'shop_type_id' => ShopType::inRandomOrder()->first()->id,
            'address' => $this->faker->address(),
            'status' => $this->faker->randomElement(['pending', 'rejected', 'active', 'inactive']),
        ];
    }
}

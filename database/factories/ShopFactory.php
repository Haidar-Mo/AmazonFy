<?php

namespace Database\Factories;

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
            'user_id' => User::factory()->create()->id,
            'name' => $this->faker->word(),
            'phone_number' => $this->faker->phoneNumber(),
            'identity_number' => $this->faker->randomNumber(),
            'logo' => '',
            'identity_front_face' => '',
            'identity_back_face' => '',
            'type' => 'default_type',
            'address' => $this->faker->address,
            'status' => 'pending',
        ];
    }
}

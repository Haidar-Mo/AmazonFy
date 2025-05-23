<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $available_balance = $this->faker->randomFloat(1, 999.9, 999999.9);
        $marginal_balance = $this->faker->randomFloat(1, 999.9, 999999.9);
        return [
            'user_id' => User::factory(),
            'available_balance' => $available_balance,
            'marginal_balance' => $marginal_balance,
            'total_balance' => $available_balance + $marginal_balance,
        ];
    }
}

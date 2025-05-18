<?php

namespace Database\Factories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionHistory>
 */
class TransactionHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wallet_id' => Wallet::factory(),
            'amount' => $this->faker->randomFloat(1, 999999.1, 100000.1),
            'transaction_type' => 'charge',
            'target' => $this->faker->shuffleString('qwerpoiuytasdfghjkjlzxcvbnm'),
            'charge_network' => 'TRC-20',
            'coin_type' => 'USDT',
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'image' => $this->faker->imageUrl,
        ];

    }

    public function withdraw(): static
    {
        return $this->state(fn(array $attributes) => [
            'transaction_type' => 'withdraw',
            'image' => null
        ]);
    }
}

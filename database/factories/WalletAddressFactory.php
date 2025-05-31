<?php

namespace Database\Factories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WalletAddress>
 */
class WalletAddressFactory extends Factory
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
            'name' => $this->faker->word,
            'target' => $this->faker->shuffleString('qwerpoiuytasdfghjkjlzxcvbnm'),
            'network_name' => $this->faker->word(),
        ];
    }
}

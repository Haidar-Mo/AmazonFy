<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->enum('transaction_type', ['charge', 'withdraw']);
            $table->string('target'); // the wallet address
            $table->string('charge_network')->default('TRC-20');
            $table->string('coin_type')->default('USDT');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_histories');
    }
};

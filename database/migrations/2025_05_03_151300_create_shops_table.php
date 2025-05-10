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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('shop_type_id')->nullable()->constrained('shop_types','id')->nullOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('phone_number');
            $table->string('identity_number');
            $table->string('logo');
            $table->string('identity_front_face');
            $table->string('identity_back_face');
            $table->string('address');
            $table->enum('status', ['pending', 'rejected', 'active', 'inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};

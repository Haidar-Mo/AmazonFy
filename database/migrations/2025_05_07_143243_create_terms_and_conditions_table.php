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
        Schema::create('terms_and_conditions', function (Blueprint $table) {
            $table->id();
            $table->text('arabic_content')->default('مرحباً بك في أمازون-فاي');
            $table->text('english_content')->default('Welcome To AmazonFy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terms_and_conditions');
    }
};

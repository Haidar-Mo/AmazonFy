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
        Schema::create('visa_required_field_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visa_required_field_id')->constrained('visa_required_fields')->cascadeOnDelete();
            $table->string('locale');
            $table->string('label');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visa_required_field_translations');
    }
};

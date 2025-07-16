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
        Schema::create('terms_and_conditions_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('terms_and_conditions_id')
                ->constrained()
                ->onDelete('cascade')
                ->name('tc_translations_tc_id_foreign'); // short name
            $table->string('locale')->index();
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terms_and_conditions_translations');
    }
};

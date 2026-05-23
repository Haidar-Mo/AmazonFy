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
        Schema::create('air_line_required_field_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('air_line_required_field_id');
            $table->string('locale');
            $table->string('label');

            $table->foreign('air_line_required_field_id', 'alf_req_field_id_fk')
                ->references('id')
                ->on('air_line_required_fields')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('air_line_required_field_translations');
    }
};

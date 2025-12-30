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
        Schema::create('health_data', function (Blueprint $table) {
            $table->id('health_data_id');

            $table->unsignedBigInteger('personal_data_id')->index();
            $table->foreign('personal_data_id')
                ->references('personal_data_id')
                ->on('personal_data')
                ->onDelete('cascade');

            $table->text('allergies')->nullable();
            $table->text('diseases')->nullable();
            $table->text('medications')->nullable();
            $table->text('additional_information')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_data');
    }
};

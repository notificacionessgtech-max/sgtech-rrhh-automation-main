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
        Schema::create('academic_information', function (Blueprint $table) {
            $table->id('academic_information_id');

            $table->unsignedBigInteger('personal_data_id')->index();
            $table->foreign('personal_data_id')
                ->references('personal_data_id')
                ->on('personal_data')
                ->onDelete('cascade');

            // Información formal
            $table->string('academic_institution');
            $table->date('start_date_school')->nullable();
            $table->date('end_date_school')->nullable();
            $table->string('university_career')->nullable();
            $table->string('degree')->nullable();

            // Número de tarjeta profesional
            $table->string('card_number')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_information');
    }
};

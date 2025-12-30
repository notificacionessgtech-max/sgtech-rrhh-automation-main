<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('additional_education', function (Blueprint $table) {
            $table->id('additional_education_id');

            $table->unsignedBigInteger('personal_data_id')->index();
            $table->foreign('personal_data_id')
                ->references('personal_data_id')
                ->on('personal_data')
                ->onDelete('cascade');

            // Especialidad
            $table->string('specialty_institution', 100)->nullable();
            $table->date('start_date_specialty')->nullable();
            $table->date('end_date_specialty')->nullable();
            $table->string('course', 100)->nullable();
            $table->enum('specialty_level', ['basic', 'intermediate', 'advanced'])->nullable();

            // Metodologías y herramientas
            $table->string('methodology_name', 100)->nullable();
            $table->enum('proficiency_level', ['basic', 'intermediate', 'advanced'])->nullable();

            // Idiomas
            $table->string('language', 100)->nullable();
            $table->enum('language_level', ['basic', 'intermediate', 'advanced'])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('additional_education');
    }
};

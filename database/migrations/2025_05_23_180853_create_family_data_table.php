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
        Schema::create('family_data', function (Blueprint $table) {
            $table->id('family_data_id');

            $table->unsignedBigInteger('personal_data_id')->index();
            $table->foreign('personal_data_id')
                ->references('personal_data_id')
                ->on('personal_data')
                ->onDelete('cascade'); // borra familiares si se borra el personal_data

            $table->string('relationship');
            $table->string('dni')->nullable();   // opcional
            $table->string('full_name');
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('birthdate');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_data');
    }
};

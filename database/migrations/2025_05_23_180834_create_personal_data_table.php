<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('personal_data', function (Blueprint $table) {
            $table->id('personal_data_id');

            $table->unsignedBigInteger('invitation_link_id')->index();
            $table->foreign('invitation_link_id')
                ->references('id')
                ->on('invitation_links')
                ->onDelete('cascade');

            // Reclutamiento
            $table->date('hiring_date');
            $table->string('job_position', 50);

            // Datos Personales
            $table->string('first_name', 30);
            $table->string('middle_name', 30)->nullable();
            $table->string('last_name', 30);
            $table->string('second_last_name', 30)->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'free union']);
            $table->date('birthdate');
            $table->string('place_of_birth', 50);
            $table->string('eps', 50);
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);

            // Nacionalidad y contacto
            $table->string('dni', 20)->unique();
            $table->date('date_of_issue');
            $table->string('place_of_issue', 50);
            $table->string('nationality', 50);
            $table->string('address');
            $table->string('phone_number', 20);
            $table->string('email')->index();

            $table->timestamps();
        });

    }
    public function down(): void
    {
        Schema::dropIfExists('personal_data');
    }
};

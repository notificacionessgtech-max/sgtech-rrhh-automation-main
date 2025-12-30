<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('personal_documents', function (Blueprint $table) {
            $table->id('personal_document_id');

            // Relación con personal_data
            $table->unsignedBigInteger('personal_data_id')->index();
            $table->foreign('personal_data_id')
                ->references('personal_data_id')
                ->on('personal_data')
                ->onDelete('cascade');

            // Tipo de documento
            $table->enum('document_type', [
                'eps',
                'cv',
                'nit',
                'bank_cert',
                'pension_cert',
                'cesantias_cert',
                'savings_fund_cert',
                'study_cert',
            ]);

            // Ruta del archivo
            $table->string('file_path', 255);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_documents');
    }
};

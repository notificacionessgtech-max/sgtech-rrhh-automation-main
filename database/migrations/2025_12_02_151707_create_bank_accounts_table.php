<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id('bank_accounts_id');

            $table->unsignedBigInteger('personal_data_id')->index();
            $table->foreign('personal_data_id')
                ->references('personal_data_id')
                ->on('personal_data')
                ->onDelete('cascade');

            // Campos ahora opcionales para coincidir con el controlador y las reglas de validación
            $table->string('banking_entity', 50)->nullable();
            $table->string('account_number', 50)->nullable();
            $table->enum('account_type', ['current', 'savings', 'payroll'])->nullable();
            $table->string('pension_fund', 50)->nullable();
            $table->string('severance_pay_fund', 50)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};

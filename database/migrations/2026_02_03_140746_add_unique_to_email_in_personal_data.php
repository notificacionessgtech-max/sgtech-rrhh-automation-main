<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('personal_data', function (Blueprint $table) {
            // El email ya tenía un índice normal, pero lo cambiamos a UNIQUE
            $table->unique('email');
        });
    }

    public function down(): void
    {
        Schema::table('personal_data', function (Blueprint $table) {
            $table->dropUnique(['email']);
        });
    }
};

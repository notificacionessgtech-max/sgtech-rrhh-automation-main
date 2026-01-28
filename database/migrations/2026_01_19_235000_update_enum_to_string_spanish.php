<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Usamos raw SQL para evitar la dependencia doctrine/dbal que se requiere para change()

        // 1. Personal Data
        // gender: NOT NULL
        DB::statement("ALTER TABLE personal_data MODIFY gender VARCHAR(255) NOT NULL");
        // marital_status: NOT NULL
        DB::statement("ALTER TABLE personal_data MODIFY marital_status VARCHAR(255) NOT NULL");
        // blood_group: NOT NULL (A+, A-, etc. already compatible but changing to string allows flexibility)
        DB::statement("ALTER TABLE personal_data MODIFY blood_group VARCHAR(255) NOT NULL");

        // 2. Bank Accounts
        // account_type: NULLABLE
        DB::statement("ALTER TABLE bank_accounts MODIFY account_type VARCHAR(255) NULL");

        // 3. Additional Education
        // specialty_level: NULLABLE
        DB::statement("ALTER TABLE additional_education MODIFY specialty_level VARCHAR(255) NULL");
        // proficiency_level: NULLABLE
        DB::statement("ALTER TABLE additional_education MODIFY proficiency_level VARCHAR(255) NULL");
        // language_level: NULLABLE
        DB::statement("ALTER TABLE additional_education MODIFY language_level VARCHAR(255) NULL");

        // 4. Family Data
        // gender: NOT NULL
        DB::statement("ALTER TABLE family_data MODIFY gender VARCHAR(255) NOT NULL");
    }

    public function down(): void
    {
        // Revertir a ENUM es riesgoso si hay datos que no coinciden, por lo que lo dejamos como string
        // o podríamos intentar revertir si estamos seguros.
        // Por seguridad, en este script de corrección, no revertimos a ENUM estricto.
    }
};

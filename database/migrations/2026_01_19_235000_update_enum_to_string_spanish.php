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
        DB::statement("ALTER TABLE personal_data ALTER COLUMN gender TYPE VARCHAR(255), ALTER COLUMN gender SET NOT NULL");
        // marital_status: NOT NULL
        DB::statement("ALTER TABLE personal_data ALTER COLUMN marital_status TYPE VARCHAR(255), ALTER COLUMN marital_status SET NOT NULL");
        // blood_group: NOT NULL (A+, A-, etc. already compatible but changing to string allows flexibility)
        DB::statement("ALTER TABLE personal_data ALTER COLUMN blood_group TYPE VARCHAR(255), ALTER COLUMN blood_group SET NOT NULL");

        // 2. Bank Accounts
        // account_type: NULLABLE
        DB::statement("ALTER TABLE bank_accounts ALTER COLUMN account_type TYPE VARCHAR(255), ALTER COLUMN account_type DROP NOT NULL");

        // 3. Additional Education
        // specialty_level: NULLABLE
        DB::statement("ALTER TABLE additional_education ALTER COLUMN specialty_level TYPE VARCHAR(255), ALTER COLUMN specialty_level DROP NOT NULL");
        // proficiency_level: NULLABLE
        DB::statement("ALTER TABLE additional_education ALTER COLUMN proficiency_level TYPE VARCHAR(255), ALTER COLUMN proficiency_level DROP NOT NULL");
        // language_level: NULLABLE
        DB::statement("ALTER TABLE additional_education ALTER COLUMN language_level TYPE VARCHAR(255), ALTER COLUMN language_level DROP NOT NULL");

        // 4. Family Data
        // gender: NOT NULL
        DB::statement("ALTER TABLE family_data ALTER COLUMN gender TYPE VARCHAR(255), ALTER COLUMN gender SET NOT NULL");
    }

    public function down(): void
    {
        // Revertir a ENUM es riesgoso si hay datos que no coinciden, por lo que lo dejamos como string
        // o podríamos intentar revertir si estamos seguros.
        // Por seguridad, en este script de corrección, no revertimos a ENUM estricto.
    }
};

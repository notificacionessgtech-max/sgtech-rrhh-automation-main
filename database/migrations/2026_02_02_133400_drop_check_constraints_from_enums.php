<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // PostgreSQL crea restricciones de tipo CHECK para emular los ENUM de Laravel.
        // Al cambiar el tipo a VARCHAR, estas restricciones persisten y bloquean valores nuevos (como el español).

        // 1. Personal Data
        DB::statement('ALTER TABLE personal_data DROP CONSTRAINT IF EXISTS personal_data_gender_check');
        DB::statement('ALTER TABLE personal_data DROP CONSTRAINT IF EXISTS personal_data_marital_status_check');
        DB::statement('ALTER TABLE personal_data DROP CONSTRAINT IF EXISTS personal_data_blood_group_check');

        // 2. Family Data
        DB::statement('ALTER TABLE family_data DROP CONSTRAINT IF EXISTS family_data_gender_check');

        // 3. Bank Accounts
        DB::statement('ALTER TABLE bank_accounts DROP CONSTRAINT IF EXISTS bank_accounts_account_type_check');

        // 4. Additional Education
        DB::statement('ALTER TABLE additional_education DROP CONSTRAINT IF EXISTS additional_education_specialty_level_check');
        DB::statement('ALTER TABLE additional_education DROP CONSTRAINT IF EXISTS additional_education_proficiency_level_check');
        DB::statement('ALTER TABLE additional_education DROP CONSTRAINT IF EXISTS additional_education_language_level_check');

        // 5. Invitation Links (por si acaso)
        DB::statement('ALTER TABLE invitation_links DROP CONSTRAINT IF EXISTS invitation_links_status_check');

        // 6. Personal Documents
        DB::statement('ALTER TABLE personal_documents DROP CONSTRAINT IF EXISTS personal_documents_document_type_check');
    }

    public function down(): void
    {
        // No es necesario restaurar las restricciones viejas ya que el sistema ahora usa strings libres.
    }
};

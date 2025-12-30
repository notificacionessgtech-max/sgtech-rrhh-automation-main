<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invitation_links', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('email')->index();

            $table->enum('status', ['pending', 'used', 'expired'])->default('pending');

            $table->timestamp('used_at')->nullable();
            $table->timestamp('verified_at')->nullable(); // clic en el enlace

            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitation_links');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            //  STI 
            $table->enum('type', ['admin', 'agent', 'client'])->default('client');

            //  Identité commune 
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('telephone', 20)->nullable();
            $table->string('adresse')->nullable();
            $table->string('photo')->nullable();

             //  Champs spécifiques Client 
            $table->date('date_naissance')->nullable();
            $table->string('cin', 30)->nullable()->comment('Carte Identité Nationale');
            $table->string('profession')->nullable();

            //  Champs spécifiques Agent 
            $table->string('matricule')->nullable()->unique();
            $table->string('zone_couverte')->nullable();

            // ── Statut & sécurité ─────────────────────────────────────────────
            $table->boolean('is_active')->default(true);
            $table->rememberToken();

            // ── Index ──────────────────────────────────────────────────────────
            $table->index('type');
            $table->index('email');

            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

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
        Schema::create('sinistres', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contrat_id')->constrained('contrats')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null')
                  ->comment('Agent chargé du dossier');

            $table->string('numero_sinistre')->unique();

            // ── Description de l'événement ─────────────────────────────────────
            $table->date('date_sinistre');
            $table->string('lieu')->nullable();
            $table->text('description');
            $table->decimal('montant_reclame', 10, 2)->nullable();
            $table->decimal('montant_accorde', 10, 2)->nullable();

            // ── Statut du dossier ──────────────────────────────────────────────
            $table->enum('statut', [
                'declare',          // vient d'être créé
                'en_instruction',   // agent examine le dossier
                'accepte',          // sinistre validé
                'refuse',           // sinistre refusé
                'indemnise',        // paiement effectué
            ])->default('declare');

            // ── Pièces jointes ─────────────────────────────────────────────────
            $table->json('documents')->nullable()->comment('Photos, PV, factures...');

            $table->text('notes_agent')->nullable();

            $table->index(['client_id', 'statut']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sinistres');
    }
};

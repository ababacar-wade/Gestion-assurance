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
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assurance_id')->constrained('assurances')->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained('users')->onDelete('set null');

            // ── Numéro unique du contrat ────────────────────────────────────────
            $table->string('numero_contrat')->unique();

            // ── Période de couverture ──────────────────────────────────────────
            $table->date('date_debut');
            $table->date('date_fin');
            $table->enum('periodicite', ['mensuel', 'trimestriel', 'annuel'])->default('mensuel');

            // ── Tarification ───────────────────────────────────────────────────
            $table->decimal('prime', 10, 2)->comment('Montant à payer par période');
            $table->decimal('franchise', 10, 2)->default(0)->comment('Part à charge du client en cas sinistre');

            // ── Statut ─────────────────────────────────────────────────────────
            $table->enum('statut', [
                'en_attente',   // souscription non encore payée
                'actif',        // payé et en cours
                'suspendu',     // paiement en retard
                'resilié',      // annulé
                'expire',       // date_fin dépassée
            ])->default('en_attente');

            // ── Documents ──────────────────────────────────────────────────────
            $table->string('document_path')->nullable()->comment('PDF du contrat signé');
            $table->json('documents_supplementaires')->nullable();

            $table->text('notes')->nullable();

            $table->index(['client_id', 'statut']);
            $table->index('numero_contrat');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};

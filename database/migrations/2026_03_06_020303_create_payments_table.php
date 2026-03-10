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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contrat_id')->constrained('contrats')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');

            // ── Référence de transaction ────────────────────────────────────────
            $table->string('reference')->unique()->comment('Référence interne unique');
            $table->string('transaction_id')->nullable()->comment('ID retourné par la passerelle');

            // ── Montant ────────────────────────────────────────────────────────
            $table->decimal('montant', 10, 2);
            $table->string('devise', 5)->default('FCFA');

            // ── Mode de paiement (simulation) ──────────────────────────────────
            $table->enum('methode', [
                'wave',
                'orange_money',
                'free_money',
                'paydunya',
                'simulation',   // pour les tests / démo
            ])->default('simulation');

            $table->string('numero_payeur', 20)->nullable()->comment('Numéro de téléphone utilisé');

            // ── Statut ─────────────────────────────────────────────────────────
            $table->enum('statut', [
                'en_attente',
                'succes',
                'echec',
                'rembourse',
            ])->default('en_attente');

            // ── Période couverte par ce paiement ───────────────────────────────
            $table->date('periode_debut')->nullable();
            $table->date('periode_fin')->nullable();

            // ── Réponse brute de la passerelle ─────────────────────────────────
            $table->json('payload_retour')->nullable()->comment('Réponse JSON de l\'API paiement');

            $table->timestamp('paye_le')->nullable();

            $table->index(['contrat_id', 'statut']);
            $table->index('reference');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

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
        Schema::create('assurances', function (Blueprint $table) {
            $table->id();

            // ── STI ──────────────────────────────────────────────────────────
            $table->enum('type', ['auto', 'habitat', 'vie']);

            // ── Infos communes ────────────────────────────────────────────────
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('prix_mensuel', 10, 2);
            $table->decimal('prix_annuel', 10, 2);
            $table->decimal('montant_couverture', 15, 2)->comment('Plafond de remboursement');
            $table->boolean('is_active')->default(true);
            $table->json('garanties')->nullable()->comment('Liste des garanties incluses');

            // ── Champs spécifiques Auto ────────────────────────────────────────
            $table->string('marque_vehicule')->nullable();
            $table->string('modele_vehicule')->nullable();
            $table->year('annee_vehicule')->nullable();
            $table->enum('type_vehicule', ['berline', 'suv', 'moto', 'camion', 'autre'])->nullable();
            $table->enum('formule_auto', ['tiers', 'tiers_etendu', 'tous_risques'])->nullable();

            // ── Champs spécifiques Habitat ─────────────────────────────────────
            $table->enum('type_habitat', ['appartement', 'maison', 'villa', 'studio'])->nullable();
            $table->decimal('surface_m2', 8, 2)->nullable();
            $table->boolean('couvre_contenu')->default(false)->comment('Couvre le mobilier');
            $table->boolean('couvre_structure')->default(false)->comment('Couvre les murs');

            // ── Champs spécifiques Vie ─────────────────────────────────────────
            $table->integer('duree_annees')->nullable()->comment('Durée du contrat vie');
            $table->decimal('capital_garanti', 15, 2)->nullable();
            $table->integer('age_min')->nullable();
            $table->integer('age_max')->nullable();


            $table->index('type');
            $table->index('is_active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assurances');
    }
};

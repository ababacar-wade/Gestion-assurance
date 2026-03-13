<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Agent;
use App\Models\Assurance;
use App\Models\Client;
use App\Models\Contrat;
use App\Models\Payment;
use App\Models\Sinistre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────────────────────────────────
        Admin::create([
            'nom'       => 'WADE',
            'prenom'    => 'Abba',
            'email'     => 'Abba@assurance.sn',
            'password'  => Hash::make('passer@2026'),
            'telephone' => '33 000 00 00',
            'adresse'   => 'Dakar, Golf',
            'is_active' => true,
        ]);

        // ── Agents ────────────────────────────────────────────────────────
        $agent1 = Agent::create([
            'nom'           => 'NDIAYE',
            'prenom'        => 'Santos',
            'email'         => 'Santos@assurance.sn',
            'password'      => Hash::make('passer@2026'),
            'telephone'     => '77 000 00 00',
            'adresse'       => 'Dakar, Diamndiadio',
            'matricule'     => 'AGT-001',
            'zone_couverte' => 'Dakar',
            'is_active'     => true,
        ]);

        $agent2 = Agent::create([
            'nom'           => 'WADE',
            'prenom'        => 'Khady',
            'email'         => 'Khady@assurance.sn',
            'password'      => Hash::make('passer@2026'),
            'telephone'     => '76 001 00 00',
            'adresse'       => 'Darou Khoudoss',
            'matricule'     => 'AGT-002',
            'zone_couverte' => 'Thiès, Tivaoune',
            'is_active'     => true,
        ]);

        // ── Clients ───────────────────────────────────────────────────────
        $client1 = Client::create([
            'nom'            => 'WADE',
            'prenom'         => 'Cheikh',
            'email'          => 'Cheikh@test.sn',
            'password'       => Hash::make('passer@2026'),
            'telephone'      => '70 002 00 00',
            'adresse'        => 'Darou, Mboro',
            'date_naissance' => '2006-01-02',
            'cin'            => '2006-00001-12345',
            'profession'     => 'Ingénieur',
            'is_active'      => true,
        ]);

        $client2 = Client::create([
            'nom'            => 'Wade',
            'prenom'         => 'Mbacke',
            'email'          => 'Mbacke@test.sn',
            'password'       => Hash::make('passer@2026'),
            'telephone'      => '78 003 00 00',
            'adresse'        => 'Thiès',
            'date_naissance' => '1999-01-01',
            'cin'            => '1999-00001-67890',
            'profession'     => 'laveur',
            'is_active'      => true,
        ]);

        // ── Assurances ────────────────────────────────────────────────────
        $autoTiers = Assurance::create([
            'type'               => 'auto',
            'nom'                => 'Auto Tiers Simple',
            'description'        => 'Couverture responsabilité civile obligatoire pour tous les véhicules.',
            'prix_mensuel'       => 15000,
            'prix_annuel'        => 162000,
            'montant_couverture' => 5000000,
            'is_active'          => true,
            'garanties'          => [
                'Responsabilité civile',
                'Défense et recours',
                'Assistance 24/7',
                'Protection juridique',
            ],
        ]);

        $autoTousRisques = Assurance::create([
            'type'               => 'auto',
            'nom'                => 'Auto Tous Risques',
            'description'        => 'Couverture complète pour votre véhicule, tous risques inclus.',
            'prix_mensuel'       => 35000,
            'prix_annuel'        => 378000,
            'montant_couverture' => 15000000,
            'is_active'          => true,
            'garanties'          => [
                'Responsabilité civile',
                'Dommages tous accidents',
                'Vol et incendie',
                'Bris de glace',
                'Assistance 24/7',
                'Véhicule de remplacement',
            ],
        ]);

        $habitatEssentiel = Assurance::create([
            'type'               => 'habitat',
            'nom'                => 'Habitat Essentiel',
            'description'        => 'Protection de base pour votre logement contre les risques courants.',
            'prix_mensuel'       => 8000,
            'prix_annuel'        => 86400,
            'montant_couverture' => 10000000,
            'is_active'          => true,
            'garanties'          => [
                'Incendie et explosion',
                'Dégâts des eaux',
                'Vol et cambriolage',
                'Responsabilité civile',
            ],
        ]);

        $habitatPremium = Assurance::create([
            'type'               => 'habitat',
            'nom'                => 'Habitat Premium',
            'description'        => 'Protection complète avec couverture étendue et assistance incluse.',
            'prix_mensuel'       => 18000,
            'prix_annuel'        => 194400,
            'montant_couverture' => 30000000,
            'is_active'          => true,
            'garanties'          => [
                'Incendie et explosion',
                'Dégâts des eaux',
                'Vol et cambriolage',
                'Catastrophes naturelles',
                'Bris de glace',
                'Assistance ménagère',
                'Responsabilité civile étendue',
            ],
        ]);

        $vieSerenite = Assurance::create([
            'type'               => 'vie',
            'nom'                => 'Vie Sérénité 20 ans',
            'description'        => 'Capital garanti à vos bénéficiaires en cas de décès ou d\'invalidité totale.',
            'prix_mensuel'       => 12000,
            'prix_annuel'        => 129600,
            'montant_couverture' => 20000000,
            'is_active'          => true,
            'duree_annees'       => 20,
            'capital_garanti'    => 20000000,
            'age_min'            => 18,
            'age_max'            => 60,
            'garanties'          => [
                'Décès toutes causes',
                'Invalidité absolue et définitive',
                'Capital garanti',
                'Double capital accident',
            ],
        ]);

        // ── Contrats de démo ──────────────────────────────────────────────
        $contrat1 = Contrat::create([
            'client_id'    => $client1->id,
            'assurance_id' => $autoTiers->id,
            'agent_id'     => $agent1->id,
            'date_debut'   => now()->subMonths(6),
            'date_fin'     => now()->addMonths(6),
            'periodicite'  => 'mensuel',
            'prime'        => 15000,
            'franchise'    => 50000,
            'statut'       => 'actif',
        ]);

        $contrat2 = Contrat::create([
            'client_id'    => $client1->id,
            'assurance_id' => $habitatEssentiel->id,
            'agent_id'     => $agent1->id,
            'date_debut'   => now()->subMonths(3),
            'date_fin'     => now()->addMonths(9),
            'periodicite'  => 'mensuel',
            'prime'        => 8000,
            'franchise'    => 25000,
            'statut'       => 'en_attente',
        ]);

        $contrat3 = Contrat::create([
            'client_id'    => $client2->id,
            'assurance_id' => $autoTousRisques->id,
            'agent_id'     => $agent2->id,
            'date_debut'   => now()->subYear(),
            'date_fin'     => now()->addMonths(3),
            'periodicite'  => 'annuel',
            'prime'        => 378000 * 0.9,
            'franchise'    => 100000,
            'statut'       => 'actif',
        ]);

        // ── Paiements de démo ─────────────────────────────────────────────
        Payment::create([
            'contrat_id'     => $contrat1->id,
            'client_id'      => $client1->id,
            'reference'      => 'PAY-' . strtoupper(Str::random(10)),
            'montant'        => 15000,
            'methode'        => 'simulation',
            'statut'         => 'succes',
            'paye_le'        => now()->subMonths(6),
            'periode_debut'  => now()->subMonths(6),
            'periode_fin'    => now()->subMonths(5),
            'transaction_id' => 'SIM-' . strtoupper(Str::random(8)),
        ]);

        Payment::create([
            'contrat_id'    => $contrat3->id,
            'client_id'     => $client2->id,
            'reference'     => 'PAY-' . strtoupper(Str::random(10)),
            'montant'       => 340200,
            'methode'       => 'wave',
            'statut'        => 'succes',
            'paye_le'       => now()->subYear(),
            'periode_debut' => now()->subYear(),
            'periode_fin'   => now(),
            'transaction_id'=> 'WAV-' . strtoupper(Str::random(8)),
        ]);

        // ── Sinistre de démo ──────────────────────────────────────────────
        Sinistre::create([
            'contrat_id'      => $contrat1->id,
            'client_id'       => $client1->id,
            'agent_id'        => $agent1->id,
            'date_sinistre'   => now()->subWeeks(2),
            'lieu'            => 'Dakar, Autoroute VDN',
            'description'     => 'Collision avec un autre véhicule à l\'intersection. Dommages sur le pare-chocs avant et capot.',
            'montant_reclame' => 850000,
            'montant_accorde' => 750000,
            'statut'          => 'accepte',
            'notes_agent'     => 'Dossier complet. Dommages évalués à 750 000 XOF après expertise.',
        ]);
    }
}
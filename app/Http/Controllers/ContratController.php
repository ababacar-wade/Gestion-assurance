<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Assurance;
use App\Models\Contrat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContratController extends Controller
{
    // ── Liste ─────────────────────────────────────────────────
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $contrats = match($user->type) {
            'admin'  => Contrat::with(['client', 'assurance', 'agent'])->latest()->paginate(15),
            'agent'  => Contrat::where('agent_id', $user->id)->with(['client', 'assurance'])->latest()->paginate(15),
            default  => Contrat::where('client_id', $user->id)->with('assurance')->latest()->paginate(15),
        };

        return view('contrats.index', compact('contrats'));
    }

    // ── Formulaire de souscription ────────────────────────────
    public function create()
    {
        $assurances = Assurance::actives()->get();
        return view('contrats.create', compact('assurances'));
    }

    // ── Enregistrement ────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'assurance_id' => ['required', 'exists:assurances,id'],
            'date_debut'   => ['required', 'date', 'after_or_equal:today'],
            'periodicite'  => ['required', 'in:mensuel,trimestriel,annuel'],
        ]);

        $assurance = Assurance::findOrFail($data['assurance_id']);

        // Calcul automatique date_fin et prime selon périodicité
        $dateDebut = \Carbon\Carbon::parse($data['date_debut']);

        [$dateFin, $prime] = match($data['periodicite']) {
            'trimestriel' => [$dateDebut->copy()->addMonths(3),  $assurance->prix_mensuel * 3],
            'annuel'      => [$dateDebut->copy()->addYear(),      $assurance->prix_annuel],
            default       => [$dateDebut->copy()->addMonth(),     $assurance->prix_mensuel],
        };

        $contrat = Contrat::create([
            'client_id'    => Auth::id(),
            'assurance_id' => $assurance->id,
            'date_debut'   => $dateDebut,
            'date_fin'     => $dateFin,
            'periodicite'  => $data['periodicite'],
            'prime'        => $prime,
            'statut'       => 'en_attente',
        ]);

        return redirect()->route('client.payments.store', $contrat)
            ->with('info', 'Contrat créé. Procédez au paiement.');
    }

    // ── Détail ────────────────────────────────────────────────
    public function show(Contrat $contrat)
    {
        $this->authorizeContrat($contrat);

        $contrat->load(['assurance', 'client', 'agent', 'payments', 'sinistres']);

        return view('contrats.show', compact('contrat'));
    }

    // ── Formulaire modification (Admin/Agent) ─────────────────
    public function edit(Contrat $contrat)
    {
        $agents = Agent::actifs()->get();
        return view('contrats.edit', compact('contrat', 'agents'));
    }

    // ── Mise à jour (Admin/Agent) ─────────────────────────────
    public function update(Request $request, Contrat $contrat)
    {
        $data = $request->validate([
            'statut'   => ['required', 'in:en_attente,actif,suspendu,resilié,expire'],
            'agent_id' => ['nullable', 'exists:users,id'],
            'notes'    => ['nullable', 'string'],
        ]);

        $contrat->update($data);

        return redirect()->route('contrats.show', $contrat)
            ->with('success', 'Contrat mis à jour.');
    }

    // ── Résiliation (Admin) ───────────────────────────────────
    public function destroy(Contrat $contrat)
    {
        $contrat->update(['statut' => 'resilié']);

        return redirect()->route('admin.contrats.index')
            ->with('success', 'Contrat résilié.');
    }

    // ── Sécurité : vérifier que le contrat appartient au user ─
    private function authorizeContrat(Contrat $contrat): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $autorise = match($user->type) {
            'admin' => true,
            'agent' => $contrat->agent_id === $user->id,
            default => $contrat->client_id === $user->id,
        };

        abort_unless($autorise, 403, 'Accès non autorisé.');
    }
}

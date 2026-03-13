<?php

namespace App\Http\Controllers;

use App\Models\Assurance;
use App\Models\Contrat;
use App\Models\Client;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContratController extends Controller
{
    // ── Liste ──────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = match($user->type) {
            'admin'  => Contrat::with(['client', 'assurance', 'agent']),
            'agent'  => Contrat::where('agent_id', $user->id)->with(['client', 'assurance']),
            default  => Contrat::where('client_id', $user->id)->with('assurance'),
        };

        if ($request->filled('statut') && $request->statut !== 'tous') {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('q')) {
            $query->where('numero_contrat', 'like', '%' . $request->q . '%');
        }

        $contrats = $query->latest()->paginate(15);

        $view = match($user->type) {
            'admin'  => 'admin.contrats.index',
            'agent'  => 'agent.contrats.index',
            default  => 'client.contrats.index',
        };

        return view($view, compact('contrats'));
    }

    // ── Formulaire création ────────────────────────────────────────────────
    public function create()
    {
        $assurances = Assurance::actives()->get();
        return view('client.contrats.create', compact('assurances'));
    }

    // ── Enregistrement ────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'assurance_id' => ['required', 'exists:assurances,id'],
            'date_debut'   => ['required', 'date', 'after_or_equal:today'],
            'periodicite'  => ['required', 'in:mensuel,trimestriel,annuel'],
        ]);

        $assurance = Assurance::findOrFail($data['assurance_id']);

        // Calcul prime selon périodicité
        $prime = match($data['periodicite']) {
            'trimestriel' => $assurance->prix_mensuel * 3 * 0.95,
            'annuel'      => $assurance->prix_annuel  * 0.9,
            default       => $assurance->prix_mensuel,
        };

        // Calcul date de fin
        $dateFin = match($data['periodicite']) {
            'trimestriel' => \Carbon\Carbon::parse($data['date_debut'])->addMonths(3),
            'annuel'      => \Carbon\Carbon::parse($data['date_debut'])->addYear(),
            default       => \Carbon\Carbon::parse($data['date_debut'])->addMonth(),
        };

        // Assigner un agent disponible (le moins chargé)
        $agent = Agent::withoutGlobalScopes()
            ->where('is_active', true)
            ->withCount(['contrats' => fn($q) => $q->where('statut', 'actif')])
            ->orderBy('contrats_count')
            ->first();

        $contrat = Contrat::create([
            'client_id'    => Auth::id(),
            'assurance_id' => $assurance->id,
            'agent_id'     => $agent?->id,
            'date_debut'   => $data['date_debut'],
            'date_fin'     => $dateFin,
            'periodicite'  => $data['periodicite'],
            'prime'        => round($prime, 2),
            'statut'       => 'en_attente',
        ]);

        return redirect()->route('client.contrats.show', $contrat)
            ->with('success', 'Contrat créé ! Procédez au paiement pour l\'activer.');
    }

    // ── Détail ────────────────────────────────────────────────────────────
    public function show(Contrat $contrat)
    {
        $this->authorizeContrat($contrat);
        $contrat->load(['assurance', 'client', 'agent', 'payments', 'sinistres']);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $view = match($user->type) {
            'admin'  => 'admin.contrats.show',
            'agent'  => 'agent.contrats.show',
            default  => 'client.contrats.show',
        };

        return view($view, compact('contrat'));
    }

    // ── Formulaire édition ────────────────────────────────────────────────
    public function edit(Contrat $contrat)
    {
        $this->authorizeContrat($contrat);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $view = match($user->type) {
            'admin' => 'admin.contrats.edit',
            default => 'agent.contrats.edit',
        };

        return view($view, compact('contrat'));
    }

    // ── Mise à jour ───────────────────────────────────────────────────────
    public function update(Request $request, Contrat $contrat)
    {
        $this->authorizeContrat($contrat);

        $data = $request->validate([
            'statut' => ['required', 'in:en_attente,actif,suspendu,resilié,expire'],
            'notes'  => ['nullable', 'string'],
        ]);

        $contrat->update($data);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $route = match($user->type) {
            'admin' => 'admin.contrats.show',
            default => 'agent.contrats.show',
        };

        return redirect()->route($route, $contrat)
            ->with('success', 'Contrat mis à jour.');
    }

    // ── Suppression (admin seulement) ─────────────────────────────────────
    public function destroy(Contrat $contrat)
    {
        abort_unless(Auth::user()->type === 'admin', 403);
        $contrat->delete();
        return redirect()->route('admin.contrats.index')
            ->with('success', 'Contrat supprimé.');
    }

    // ── Sécurité ──────────────────────────────────────────────────────────
    private function authorizeContrat(Contrat $contrat): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $autorise = match($user->type) {
            'admin'  => true,
            'agent'  => $contrat->agent_id === $user->id,
            default  => $contrat->client_id === $user->id,
        };

        abort_unless($autorise, 403);
    }
}
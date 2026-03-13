<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Contrat;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Ce contrôleur gère tout ce qui concerne les agents.
 * Un agent peut voir ses statistiques (dashboard) et ses clients.
 * L'admin peut aussi utiliser ce contrôleur pour créer ou modifier des agents.
 */
class AgentController extends Controller
{
    // ── Dashboard agent ───────────────────────────────────────────────────
    /**
     * Affiche la page d'accueil personnalisée de l'agent avec ses chiffres clés.
     */
    public function dashboard()
    {
        /** @var \App\Models\Agent $agent */
        // On récupère l'agent qui est actuellement connecté.
        $agent = Auth::user();

        // On récupère tous ses contrats avec les infos de l'assurance et du client rattachés.
        $contrats         = $agent->contrats()->with(['assurance', 'client'])->latest()->get();
        // On compte combien de ces contrats sont actuellement "actifs".
        $contratsActifs   = $contrats->where('statut', 'actif')->count();
        // On compte les sinistres (accidents) qui ne sont pas encore réglés (ni payés, ni refusés).
        $sinistresEnCours = $agent->sinistres()
            ->whereNotIn('statut', ['indemnise', 'refuse'])->count();
        // On compte combien de clients différents l'agent gère (sans doublons).
        $nbClients        = $agent->contrats()->distinct('client_id')->count('client_id');

        // On envoie toutes ces statistiques à la vue du dashboard.
        return view('agent.dashboard', compact(
            'agent', 'contrats', 'contratsActifs', 'sinistresEnCours', 'nbClients'
        ));
    }

    // ── Liste des clients de l'agent ──────────────────────────────────────
    /**
     * Liste tous les clients qui ont au moins un contrat avec cet agent.
     */
    public function clientsIndex()
    {
        /** @var \App\Models\Agent $agent */
        $agent = Auth::user();

        // On cherche les clients qui ont des contrats gérés par cet agent précis.
        $clients = Client::withoutGlobalScopes()
            ->whereHas('contrats', fn($q) => $q->where('agent_id', $agent->id))
            ->with(['contrats' => fn($q) => $q->where('agent_id', $agent->id)])
            ->paginate(15); // On affiche 15 clients par page.

        return view('agent.clients.index', compact('clients'));
    }

    // ── Détail client (vue agent) ─────────────────────────────────────────
    /**
     * Affiche la fiche complète d'un client précis pour l'agent.
     */
    public function clientShow(Client $client)
    {
        // On charge tout l'historique du client d'un coup (contrats, sinistres, paiements).
        $client->load(['contrats.assurance', 'sinistres', 'payments']);
        return view('agent.clients.show', compact('client'));
    }

    // ── CRUD Admin : liste ────────────────────────────────────────────────
    /**
     * Liste tous les agents du système (réservé à l'admin).
     */
    public function index()
    {
        // On récupère uniquement les utilisateurs qui ont le type 'agent'.
        $agents = Agent::withoutGlobalScopes()
            ->where('type', 'agent')
            // Pour chaque agent, on compte ses contrats actifs pour voir s'il travaille bien !
            ->withCount(['contrats as nb_contrats_actifs' => fn($q) => $q->where('statut', 'actif')])
            ->latest()
            ->paginate(15);

        return view('admin.agents.index', compact('agents'));
    }

    // ── CRUD Admin : formulaire création ──────────────────────────────────
    /**
     * Affiche le formulaire pour embaucher/créer un nouvel agent.
     */
    public function create()
    {
        return view('admin.agents.create');
    }

    // ── CRUD Admin : enregistrement ───────────────────────────────────────
    /**
     * Enregistre un nouvel agent dans la base.
     */
    public function store(Request $request)
    {
        // On vérifie que les infos sont complètes et que l'email n'est pas déjà pris.
        $data = $request->validate([
            'nom'           => ['required', 'string', 'max:100'],
            'prenom'        => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'telephone'     => ['nullable', 'string', 'max:20'],
            'matricule'     => ['nullable', 'string', 'unique:users,matricule'],
            'zone_couverte' => ['nullable', 'string'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Création de l'agent avec cryptage du mot de passe.
        Agent::create([
            'nom'           => $data['nom'],
            'prenom'        => $data['prenom'],
            'email'         => $data['email'],
            'telephone'     => $data['telephone'] ?? null,
            'matricule'     => $data['matricule'] ?? null,
            'zone_couverte' => $data['zone_couverte'] ?? null,
            'password'      => Hash::make($data['password']),
            'is_active'     => true,
        ]);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent créé avec succès.');
    }

    // ── CRUD Admin : détail ───────────────────────────────────────────────
    /**
     * Affiche le profil détaillé d'un agent (côté admin).
     */
    public function show(Agent $agent)
    {
        // On regarde ce que cet agent gère comme contrats et sinistres.
        $agent->load(['contrats.client', 'sinistres']);
        return view('admin.agents.show', compact('agent'));
    }

    // ── CRUD Admin : formulaire édition ──────────────────────────────────
    /**
     * Formulaire pour modifier les infos d'un agent.
     */
    public function edit(Agent $agent)
    {
        return view('admin.agents.edit', compact('agent'));
    }

    // ── CRUD Admin : mise à jour ──────────────────────────────────────────
    /**
     * Enregistre les modifications sur un agent.
     */
    public function update(Request $request, Agent $agent)
    {
        // Validation des champs modifiables.
        $data = $request->validate([
            'nom'           => ['required', 'string', 'max:100'],
            'prenom'        => ['required', 'string', 'max:100'],
            'telephone'     => ['nullable', 'string', 'max:20'],
            'zone_couverte' => ['nullable', 'string'],
            'is_active'     => ['sometimes', 'boolean'],
        ]);

        // Mise à jour en base de données.
        $agent->update([
            ...$data,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.agents.show', $agent)
            ->with('success', 'Agent mis à jour.');
    }

    // ── CRUD Admin : suppression ──────────────────────────────────────────
    /**
     * Supprime un agent du système.
     */
    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent supprimé.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Sinistre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SinistreController extends Controller
{
    // ── Liste ──────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = match($user->type) {
            'admin' => Sinistre::with(['client', 'contrat.assurance', 'agent']),
            'agent' => Sinistre::where('agent_id', $user->id)->with(['client', 'contrat']),
            default => Sinistre::where('client_id', $user->id)->with('contrat.assurance'),
        };

        if ($request->filled('statut') && $request->statut !== 'tous') {
            $query->where('statut', $request->statut);
        }

        $sinistres = $query->latest()->paginate(15);

        $view = match($user->type) {
            'admin' => 'admin.sinistres.index',
            'agent' => 'agent.sinistres.index',
            default => 'client.sinistres.index',
        };

        return view($view, compact('sinistres'));
    }

    // ── Formulaire déclaration (client) ───────────────────────────────────
    public function create()
    {
        $contrats = Contrat::where('client_id', Auth::id())
            ->where('statut', 'actif')
            ->with('assurance')
            ->get();

        return view('client.sinistres.create', compact('contrats'));
    }

    // ── Enregistrement déclaration ────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'contrat_id'      => ['required', 'exists:contrats,id'],
            'date_sinistre'   => ['required', 'date', 'before_or_equal:today'],
            'lieu'            => ['nullable', 'string', 'max:255'],
            'description'     => ['required', 'string', 'min:20'],
            'montant_reclame' => ['nullable', 'numeric', 'min:0'],
            'documents.*'     => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        // Vérifier que le contrat appartient au client et est actif
        $contrat = Contrat::where('id', $data['contrat_id'])
            ->where('client_id', Auth::id())
            ->where('statut', 'actif')
            ->firstOrFail();

        // Upload documents
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $documents[] = $file->store('sinistres', 'public');
            }
        }

        Sinistre::create([
            ...$data,
            'client_id' => Auth::id(),
            'documents' => $documents,
            'statut'    => 'declare',
        ]);

        return redirect()->route('client.sinistres.index')
            ->with('success', 'Sinistre déclaré avec succès. Un agent vous contactera.');
    }

    // ── Détail ────────────────────────────────────────────────────────────
    public function show(Sinistre $sinistre)
    {
        $this->authorizeSinistre($sinistre);
        $sinistre->load(['contrat.assurance', 'client', 'agent']);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $view = match($user->type) {
            'admin' => 'admin.sinistres.show',
            'agent' => 'agent.sinistres.show',
            default => 'client.sinistres.show',
        };

        return view($view, compact('sinistre'));
    }

    // ── Formulaire instruction (agent/admin) ──────────────────────────────
    public function edit(Sinistre $sinistre)
    {
        $this->authorizeSinistre($sinistre);
        $sinistre->load(['contrat.assurance', 'client']);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $view = match($user->type) {
            'admin' => 'admin.sinistres.edit',
            default => 'agent.sinistres.edit',
        };

        return view($view, compact('sinistre'));
    }

    // ── Mise à jour instruction ────────────────────────────────────────────
    public function update(Request $request, Sinistre $sinistre)
    {
        $this->authorizeSinistre($sinistre);

        $data = $request->validate([
            'statut'          => ['required', 'in:declare,en_instruction,accepte,refuse,indemnise'],
            'montant_accorde' => ['nullable', 'numeric', 'min:0'],
            'notes_agent'     => ['nullable', 'string'],
        ]);

        $sinistre->update([
            ...$data,
            'agent_id' => Auth::id(),
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $route = match($user->type) {
            'admin' => 'admin.sinistres.show',
            default => 'agent.sinistres.show',
        };

        return redirect()->route($route, $sinistre)
            ->with('success', 'Sinistre mis à jour.');
    }

    // ── Sécurité ──────────────────────────────────────────────────────────
    private function authorizeSinistre(Sinistre $sinistre): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $autorise = match($user->type) {
            'admin' => true,
            'agent' => $sinistre->agent_id === $user->id || $sinistre->agent_id === null,
            default => $sinistre->client_id === $user->id,
        };

        abort_unless($autorise, 403);
    }
}
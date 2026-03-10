<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Sinistre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SinistreController extends Controller
{
    // ── Liste ─────────────────────────────────────────────────
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $sinistres = match($user->type) {
            'admin'  => Sinistre::with(['client', 'contrat.assurance', 'agent'])->latest()->paginate(15),
            'agent'  => Sinistre::where('agent_id', $user->id)->with(['client', 'contrat'])->latest()->paginate(15),
            default  => Sinistre::where('client_id', $user->id)->with('contrat.assurance')->latest()->paginate(15),
        };

        return view('sinistres.index', compact('sinistres'));
    }

    // ── Formulaire déclaration ────────────────────────────────
    public function create()
    {
        $contrats = Contrat::where('client_id', Auth::id())
            ->where('statut', 'actif')
            ->with('assurance')
            ->get();

        return view('sinistres.create', compact('contrats'));
    }

    // ── Enregistrement déclaration ────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'contrat_id'     => ['required', 'exists:contrats,id'],
            'date_sinistre'  => ['required', 'date', 'before_or_equal:today'],
            'lieu'           => ['nullable', 'string', 'max:255'],
            'description'    => ['required', 'string', 'min:20'],
            'montant_reclame'=> ['nullable', 'numeric', 'min:0'],
            'documents.*'    => ['nullable', 'file', 'mimes:jpg,png,pdf', 'max:5120'],
        ]);

        // Vérifier que le contrat appartient au client
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
            ->with('success', 'Sinistre déclaré. Un agent vous contactera.');
    }

    // ── Détail ────────────────────────────────────────────────
    public function show(Sinistre $sinistre)
    {
        $this->authorizeSinistre($sinistre);
        $sinistre->load(['contrat.assurance', 'client', 'agent']);
        return view('sinistres.show', compact('sinistre'));
    }

    // ── Traitement (Agent/Admin) ──────────────────────────────
    public function edit(Sinistre $sinistre)
    {
        return view('sinistres.edit', compact('sinistre'));
    }

    public function update(Request $request, Sinistre $sinistre)
    {
        $data = $request->validate([
            'statut'          => ['required', 'in:declare,en_instruction,accepte,refuse,indemnise'],
            'montant_accorde' => ['nullable', 'numeric', 'min:0'],
            'notes_agent'     => ['nullable', 'string'],
        ]);

        $sinistre->update([
            ...$data,
            'agent_id' => Auth::id(),
        ]);

        return redirect()->route('sinistres.show', $sinistre)
            ->with('success', 'Sinistre mis à jour.');
    }

    // ── Sécurité ──────────────────────────────────────────────
    private function authorizeSinistre(Sinistre $sinistre): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $autorise = match($user->type) {
            'admin' => true,
            'agent' => $sinistre->agent_id === $user->id,
            default => $sinistre->client_id === $user->id,
        };

        abort_unless($autorise, 403);
    }
}

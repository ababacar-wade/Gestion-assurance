<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Contrat;
use App\Models\Sinistre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    
    // ── Dashboard ─────────────────────────────────────────────
    public function dashboard()
    {
        /** @var \App\Models\Agent $agent */
        $agent = Auth::user();

        $contrats         = $agent->contrats()->with(['assurance', 'client'])->latest()->get();
        $contratsActifs   = $contrats->where('statut', 'actif')->count();
        $sinistresEnCours = $agent->sinistres()
            ->whereNotIn('statut', ['indemnise', 'refuse'])->count();
        $nbClients        = $agent->contrats()->distinct('client_id')->count();

        return view('agent.dashboard', compact(
            'agent', 'contrats', 'contratsActifs', 'sinistresEnCours', 'nbClients'
        ));
    }

    // ── CRUD (Admin) ──────────────────────────────────────────
    public function index()
    {
        $agents = Agent::latest()->paginate(15);
        return view('admin.agents.index', compact('agents'));
    }

    public function show(Agent $agent)
    {
        $agent->load(['contrats.client', 'sinistres']);
        return view('admin.agents.show', compact('agent'));
    }

    public function edit(Agent $agent)
    {
        return view('admin.agents.edit', compact('agent'));
    }

    public function update(Request $request, Agent $agent)
    {
        $data = $request->validate([
            'nom'          => ['required', 'string', 'max:100'],
            'prenom'       => ['required', 'string', 'max:100'],
            'telephone'    => ['required', 'string', 'max:20'],
            'zone_couverte'=> ['nullable', 'string'],
            'is_active'    => ['boolean'],
        ]);

        $agent->update($data);

        return redirect()->route('admin.agents.show', $agent)
            ->with('success', 'Agent mis à jour.');
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent supprimé.');
    }
}

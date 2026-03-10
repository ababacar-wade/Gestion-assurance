<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contrat;
use App\Models\Sinistre;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
   // ── Dashboard ─────────────────────────────────────────────
    public function dashboard()
    {
        /** @var \App\Models\Client $client */
        $client = Auth::user();

        $contrats         = $client->contrats()->with('assurance')->latest()->get();
        $contratsActifs   = $contrats->where('statut', 'actif');
        $sinistresEnCours = $client->sinistres()
            ->whereNotIn('statut', ['indemnise', 'refuse'])->count();

        return view('client.dashboard', compact(
            'client', 'contrats', 'contratsActifs', 'sinistresEnCours'
        ));
    }

    // ── Liste des clients (Admin/Agent) ───────────────────────
    public function index()
    {
        $clients = Client::latest()->paginate(15);
        return view('admin.clients.index', compact('clients'));
    }

    public function show(Client $client)
    {
        $client->load(['contrats.assurance', 'sinistres', 'payments']);
        return view('admin.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'nom'       => ['required', 'string', 'max:100'],
            'prenom'    => ['required', 'string', 'max:100'],
            'telephone' => ['required', 'string', 'max:20'],
            'adresse'   => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $client->update($data);

        return redirect()->route('admin.clients.show', $client)
            ->with('success', 'Client mis à jour.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('admin.clients.index')
            ->with('success', 'Client supprimé.');
    }
}

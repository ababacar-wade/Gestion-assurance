<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Agent;
use App\Models\Client;
use App\Models\Contrat;
use App\Models\Payment;
use App\Models\Sinistre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::user();

        $stats = [
            'total_clients'     => Client::count(),
            'total_agents'      => Agent::count(),
            'contrats_actifs'   => Contrat::where('statut', 'actif')->count(),
            'sinistres_ouverts' => Sinistre::whereNotIn('statut', ['indemnise', 'refuse'])->count(),
            'revenus_mois'      => Payment::where('statut', 'succes')
                                    ->whereMonth('created_at', now()->month)
                                    ->sum('montant'),
        ];

        $derniersContrats  = Contrat::with(['client', 'assurance'])->latest()->take(5)->get();
        $derniersSinistres = Sinistre::with(['client', 'contrat'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'derniersContrats', 'derniersSinistres'
        ));
    }
}

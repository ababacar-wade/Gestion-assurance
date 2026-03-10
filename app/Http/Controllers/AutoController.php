<?php

namespace App\Http\Controllers;

use App\Models\Auto;
use Illuminate\Http\Request;

class AutoController extends Controller
{
    // ── Catalogue public des assurances Auto ──────────────────
    public function index()
    {
        $autos = Auto::actifs()->get();
        return view('assurances.auto.index', compact('autos'));
    }

    public function show(Auto $auto)
    {
        return view('assurances.auto.show', compact('auto'));
    }

    // ── Calcul de prime dynamique (appelé via formulaire) ─────
    public function calculerPrime(Request $request)
    {
        $data = $request->validate([
            'assurance_id'   => ['required', 'exists:assurances,id'],
            'annee_vehicule' => ['required', 'integer', 'min:1990', 'max:' . now()->year],
            'formule'        => ['required', 'in:tiers,tiers_etendu,tous_risques'],
        ]);

        $auto  = Auto::findOrFail($data['assurance_id']);
        $prime = $auto->calculerPrime($data['annee_vehicule'], $data['formule']);

        return response()->json([
            'prime_mensuelle' => number_format($prime, 2),
            'prime_annuelle'  => number_format($prime * 12 * 0.9, 2), // 10% réduction annuel
        ]);
    }
}

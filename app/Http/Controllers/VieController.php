<?php

namespace App\Http\Controllers;

use App\Models\Vie;
use Illuminate\Http\Request;

class VieController extends Controller
{
    // ── Catalogue public des assurances Vie ───────────────────
    public function index()
    {
        $vies = Vie::actifs()->get();
        return view('assurances.vie.index', compact('vies'));
    }

    public function show(Vie $vie)
    {
        return view('assurances.vie.show', compact('vie'));
    }

    // ── Calcul de prime dynamique ─────────────────────────────
    public function calculerPrime(Request $request)
    {
        $data = $request->validate([
            'assurance_id' => ['required', 'exists:assurances,id'],
            'age'          => ['required', 'integer', 'min:18', 'max:70'],
        ]);

        $vie   = Vie::findOrFail($data['assurance_id']);

        // Vérifier que l'âge est dans la tranche acceptée
        abort_if(
            $data['age'] < $vie->age_min || $data['age'] > $vie->age_max,
            422,
            "Votre âge ({$data['age']} ans) n'est pas éligible pour cette offre."
        );

        $prime = $vie->calculerPrime($data['age']);

        return response()->json([
            'prime_mensuelle' => number_format($prime, 2),
            'prime_annuelle'  => number_format($prime * 12 * 0.9, 2),
            'capital_garanti' => number_format($vie->capital_garanti, 2),
            'duree_annees'    => $vie->duree_annees,
        ]);
    }
}

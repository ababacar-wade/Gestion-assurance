<?php

namespace App\Http\Controllers;

use App\Models\Habitat;
use Illuminate\Http\Request;

class HabitatController extends Controller
{
    // ── Catalogue public des assurances Habitat ───────────────
    public function index()
    {
        $habitats = Habitat::actifs()->get();
        return view('assurances.habitat.index', compact('habitats'));
    }

    public function show(Habitat $habitat)
    {
        return view('assurances.habitat.show', compact('habitat'));
    }

    // ── Calcul de prime dynamique ─────────────────────────────
    public function calculerPrime(Request $request)
    {
        $data = $request->validate([
            'assurance_id' => ['required', 'exists:assurances,id'],
            'surface_m2'   => ['required', 'numeric', 'min:10', 'max:1000'],
        ]);

        $habitat = Habitat::findOrFail($data['assurance_id']);
        $prime   = $habitat->calculerPrime($data['surface_m2']);

        return response()->json([
            'prime_mensuelle' => number_format($prime, 2),
            'prime_annuelle'  => number_format($prime * 12 * 0.9, 2),
        ]);
    }
}

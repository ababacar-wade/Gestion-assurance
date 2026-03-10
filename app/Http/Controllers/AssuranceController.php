<?php

namespace App\Http\Controllers;

use App\Models\Assurance;
use Illuminate\Http\Request;

class AssuranceController extends Controller
{
    /**
     * Display the public catalog of assurances.
     */
    public function catalog()
    {
        $assurances = Assurance::actives()->get();
        return view('assurances.index', compact('assurances'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assurances = Assurance::latest()->paginate(15);
        return view('admin.assurances.index', compact('assurances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.assurances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'type'              => ['required', 'in:auto,habitat,vie'],
            'nom'               => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string'],
            'prix_mensuel'      => ['required', 'numeric', 'min:0'],
            'prix_annuel'       => ['required', 'numeric', 'min:0'],
            'montant_couverture'=> ['required', 'numeric', 'min:0'],
            'is_active'         => ['boolean'],
            'garanties'         => ['nullable', 'array'],
        ]);

        $assurance = Assurance::create($data);

        return redirect()->route('admin.assurances.show', $assurance)
            ->with('success', 'Assurance créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Assurance $assurance)
    {
        return view('admin.assurances.show', compact('assurance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assurance $assurance)
    {
        return view('admin.assurances.edit', compact('assurance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assurance $assurance)
    {
        $data = $request->validate([
            'nom'               => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string'],
            'prix_mensuel'      => ['required', 'numeric', 'min:0'],
            'prix_annuel'       => ['required', 'numeric', 'min:0'],
            'montant_couverture'=> ['required', 'numeric', 'min:0'],
            'is_active'         => ['boolean'],
            'garanties'         => ['nullable', 'array'],
        ]);

        $assurance->update($data);

        return redirect()->route('admin.assurances.show', $assurance)
            ->with('success', 'Assurance mise à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assurance $assurance)
    {
        $assurance->delete();

        return redirect()->route('admin.assurances.index')
            ->with('success', 'Assurance supprimée.');
    }
}

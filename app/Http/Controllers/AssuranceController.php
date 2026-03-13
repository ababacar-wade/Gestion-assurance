<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Ce contrôleur gère tout ce qui touche aux types d'assurances (Auto, Habitat, Vie).
 * On utilise DB directement ici pour garder un contrôle total sur les requêtes.
 */
class AssuranceController extends Controller
{
    // ── Catalogue public ──────────────────────────────────────────────────
    /**
     * Affiche les offres d'assurance au grand public.
     */
    public function catalog()
    {
        // On récupère toutes les assurances actives, triées par prix (le moins cher en premier).
        $rows = DB::table('assurances')
            ->where('is_active', true)
            ->orderBy('prix_mensuel')
            ->get();

        // On trie les résultats par catégorie pour que la vue puisse les afficher proprement.
        $assurances = [
            'auto'    => $rows->where('type', 'auto')->values(),
            'habitat' => $rows->where('type', 'habitat')->values(),
            'vie'     => $rows->where('type', 'vie')->values(),
        ];

        // On envoie ces listes à la vue 'assurances.index'.
        return view('assurances.index', compact('assurances'));
    }

    // ── Liste admin ───────────────────────────────────────────────────────
    /**
     * Liste toutes les assurances dans le panneau d'administration.
     */
    public function index()
    {
        // Ici on prend tout, même les inactives, pour que l'admin puisse les gérer.
        $rows = DB::table('assurances')->orderBy('type')->orderBy('prix_mensuel')->get();

        $assurances = [
            'auto'    => $rows->where('type', 'auto')->values(),
            'habitat' => $rows->where('type', 'habitat')->values(),
            'vie'     => $rows->where('type', 'vie')->values(),
        ];

        return view('admin.assurances.index', compact('assurances'));
    }

    // ── Formulaire création ───────────────────────────────────────────────
    /**
     * Affiche le formulaire pour créer une nouvelle offre d'assurance.
     */
    public function create()
    {
        return view('admin.assurances.create');
    }

    // ── Enregistrement ────────────────────────────────────────────────────
    /**
     * Enregistre une nouvelle assurance dans la base de données.
     */
    public function store(Request $request)
    {
        // On vérifie que toutes les données envoyées par le formulaire sont correctes.
        $data = $request->validate([
            'type'               => ['required', 'in:auto,habitat,vie'],
            'nom'                => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'prix_mensuel'       => ['required', 'numeric', 'min:0'],
            'prix_annuel'        => ['required', 'numeric', 'min:0'],
            'montant_couverture' => ['required', 'numeric', 'min:0'],
            'garanties_text'     => ['nullable', 'string'],
        ]);

        // On transforme le texte des garanties (une ligne par garantie) en un vrai tableau propre.
        $garanties = null;
        if (!empty($data['garanties_text'])) {
            $garanties = array_values(array_filter(
                array_map('trim', explode("\n", $data['garanties_text']))
            ));
        }

        // On insère les données proprement et on récupère l'identifiant généré.
        $id = DB::table('assurances')->insertGetId([
            'type'               => $data['type'],
            'nom'                => $data['nom'],
            'description'        => $data['description'] ?? null,
            'prix_mensuel'       => $data['prix_mensuel'],
            'prix_annuel'        => $data['prix_annuel'],
            'montant_couverture' => $data['montant_couverture'],
            'is_active'          => $request->boolean('is_active', true),
            'garanties'          => $garanties ? json_encode($garanties) : null, // On stocke ça en JSON dans la DB.
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        // Une fois fini, on repart sur la liste avec un petit message de succès.
        return redirect()->route('admin.assurances.index')
            ->with('success', 'Assurance créée avec succès.');
    }

    // ── Affichage ─────────────────────────────────────────────────────────
    /**
     * Voir les détails d'une assurance précise (côté admin).
     */
    public function show($id)
    {
        // On cherche l'assurance par son ID.
        $assurance = DB::table('assurances')->where('id', $id)->first();
        // Si elle n'existe pas, on renvoie une erreur 404 (non trouvé).
        abort_if(!$assurance, 404);
        
        // Si on a des garanties en JSON, on les retransforme en tableau pour l'affichage.
        if ($assurance->garanties) {
            $assurance->garanties = json_decode($assurance->garanties, true);
        }
        return view('admin.assurances.show', compact('assurance'));
    }

    // ── Formulaire édition ────────────────────────────────────────────────
    /**
     * Affiche le formulaire pour modifier une assurance existante.
     */
    public function edit($id)
    {
        $assurance = DB::table('assurances')->where('id', $id)->first();
        abort_if(!$assurance, 404);
        if ($assurance->garanties) {
            $assurance->garanties = json_decode($assurance->garanties, true);
        }
        return view('admin.assurances.edit', compact('assurance'));
    }

    // ── Mise à jour ───────────────────────────────────────────────────────
    /**
     * Enregistre les modifications apportées à une assurance.
     */
    public function update(Request $request, $id)
    {
        // Validation des nouvelles données.
        $data = $request->validate([
            'nom'                => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'prix_mensuel'       => ['required', 'numeric', 'min:0'],
            'prix_annuel'        => ['required', 'numeric', 'min:0'],
            'montant_couverture' => ['required', 'numeric', 'min:0'],
            'garanties_text'     => ['nullable', 'string'],
        ]);

        $garanties = null;
        if (!empty($data['garanties_text'])) {
            $garanties = array_values(array_filter(
                array_map('trim', explode("\n", $data['garanties_text']))
            ));
        }

        // On met à jour la ligne dans la base de données.
        DB::table('assurances')->where('id', $id)->update([
            'nom'                => $data['nom'],
            'description'        => $data['description'] ?? null,
            'prix_mensuel'       => $data['prix_mensuel'],
            'prix_annuel'        => $data['prix_annuel'],
            'montant_couverture' => $data['montant_couverture'],
            'is_active'          => $request->boolean('is_active', true),
            'garanties'          => $garanties ? json_encode($garanties) : null,
            'updated_at'         => now(),
        ]);

        return redirect()->route('admin.assurances.index')
            ->with('success', 'Assurance mise à jour.');
    }

    // ── Page publique détail assurance ───────────────────────────────────────
    /**
     * Affiche les détails d'une assurance pour les visiteurs (public).
     */
    public function publicShow($id)
    {
        $assurance = DB::table('assurances')->where('id', $id)->first();
        abort_if(!$assurance, 404);

        // On convertit le résultat en objet pour plus de facilité dans la vue.
        $assurance = (object) (array) $assurance;
        if (isset($assurance->garanties) && is_string($assurance->garanties)) {
            $assurance->garanties = json_decode($assurance->garanties, true);
        }

        // On ajoute un petit badge textuel selon le type pour faire joli.
        $assurance->badge_type = match($assurance->type) {
            'auto'    => '🚗 Auto',
            'habitat' => '🏠 Habitat',
            'vie'     => '❤️ Vie',
            default   => $assurance->type,
        };

        return view('assurances.show', compact('assurance'));
    }

    // ── Suppression ───────────────────────────────────────────────────────
    /**
     * Supprime définitivement une assurance. Attention, c'est irréversible !
     */
    public function destroy($id)
    {
        DB::table('assurances')->where('id', $id)->delete();
        return redirect()->route('admin.assurances.index')
            ->with('success', 'Assurance supprimée.');
    }
}
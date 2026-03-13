@extends('layouts.app')
@section('title', 'Nos Offres')

{{-- 
    Cette vue affiche le catalogue des assurances pour le public.
    Elle est organisée par catégories (Auto, Habitat, Vie).
--}}

@section('content')

{{-- En-tête de la page avec un dégradé léger pour le style --}}
<section style="background:linear-gradient(135deg,#FFF8F5,white); padding:4rem 0 2rem;">
    <div class="container text-center">
        <span style="background:var(--orange-light); color:var(--orange); font-size:.78rem; font-weight:700; padding:.35rem .9rem; border-radius:20px;">
            Catalogue complet
        </span>
        <h1 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:2.5rem; margin-top:.75rem;">
            Toutes nos offres d'assurance
        </h1>
        <p style="color:var(--text-muted); max-width:500px; margin:0 auto;">
            Choisissez la protection adaptée à votre situation
        </p>
    </div>
</section>

<section style="padding:3rem 0 5rem; background:white;">
    <div class="container">

        {{-- Barre de filtres pour passer d'une catégorie à l'autre sans recharger la page --}}
        <div class="d-flex justify-content-center gap-3 mb-5 flex-wrap">
            @foreach(['all'=>'Toutes','auto'=>'🚗 Auto','habitat'=>'🏠 Habitat','vie'=>'❤️ Vie'] as $val => $lbl)
            <button onclick="filterType('{{ $val }}')" id="btn-{{ $val }}"
                    style="padding:.5rem 1.25rem; border-radius:25px; font-weight:600; font-size:.875rem; cursor:pointer;
                           border:2px solid {{ $val === 'all' ? 'var(--orange)' : 'var(--gray-border)' }};
                           background:{{ $val === 'all' ? 'var(--orange)' : 'white' }};
                           color:{{ $val === 'all' ? 'white' : 'var(--text-muted)' }}; transition:all .2s;">
                {{ $lbl }}
            </button>
            @endforeach
        </div>

        {{-- Configuration des titres et icônes pour chaque section --}}
        @php
            $sections = [
                'auto'    => ['🚗', 'Auto',    'Protégez votre véhicule'],
                'habitat' => ['🏠', 'Habitat', 'Sécurisez votre foyer'],
                'vie'     => ['❤️', 'Vie',     'Assurez votre avenir'],
            ];
        @endphp

        {{-- On boucle sur chaque type d'assurance --}}
        @foreach($sections as $type => [$emoji, $libelle, $desc])
        @php $liste = $assurances[$type] ?? collect(); @endphp
        @if($liste->count() > 0)

        <div class="type-section mb-5" data-type="{{ $type }}">
            {{-- Titre de la catégorie --}}
            <div class="d-flex align-items-center gap-3 mb-4">
                <div style="width:48px;height:48px;background:var(--orange-light);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;">
                    {{ $emoji }}
                </div>
                <div>
                    <h3 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin:0;font-size:1.3rem;">
                        Assurance {{ $libelle }}
                    </h3>
                    <p style="color:var(--text-muted);font-size:.875rem;margin:0;">{{ $desc }}</p>
                </div>
            </div>

            <div class="row g-3">
                {{-- On liste chaque carte d'assurance individuelle --}}
                @foreach($liste as $a)
                @php
                    // Petit hack pour que ça marche que ce soit un objet ou un tableau (selon comment on l'a récupéré dans le Controller)
                    $id          = is_object($a) ? ($a->id ?? null)          : ($a['id'] ?? null);
                    $nom         = is_object($a) ? ($a->nom ?? '')            : ($a['nom'] ?? '');
                    $desc2       = is_object($a) ? ($a->description ?? '')    : ($a['description'] ?? '');
                    $prix        = is_object($a) ? ($a->prix_mensuel ?? 0)    : ($a['prix_mensuel'] ?? 0);
                    $couverture  = is_object($a) ? ($a->montant_couverture ?? 0) : ($a['montant_couverture'] ?? 0);
                    $garantiesRaw= is_object($a) ? ($a->garanties ?? null)    : ($a['garanties'] ?? null);
                    // On décode les garanties stockées en JSON pour les afficher un par un
                    $garanties   = is_array($garantiesRaw)
                        ? $garantiesRaw
                        : (is_string($garantiesRaw) ? json_decode($garantiesRaw, true) : []);
                @endphp
                <div class="col-md-4">
                    {{-- La carte de l'offre avec un petit effet au survol de la souris --}}
                    <div style="border:1.5px solid var(--gray-border);border-radius:20px;padding:1.75rem;height:100%;transition:all .3s;"
                         onmouseover="this.style.borderColor='var(--orange)';this.style.boxShadow='0 12px 40px rgba(255,107,43,.1)';this.style.transform='translateY(-3px)'"
                         onmouseout="this.style.borderColor='var(--gray-border)';this.style.boxShadow='none';this.style.transform='none'">

                        <h5 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:.4rem;">
                            {{ $nom }}
                        </h5>
                        <p style="color:var(--text-muted);font-size:.82rem;line-height:1.5;margin-bottom:1.25rem;">
                            {{ $desc2 }}
                        </p>

                        {{-- Liste des garanties (petites pastilles vertes ou checkmarks) --}}
                        @if($garanties)
                            @foreach($garanties as $g)
                            <div style="font-size:.8rem;color:var(--text-muted);display:flex;align-items:center;gap:.4rem;margin-bottom:.35rem;">
                                <i class="bi bi-check2-circle" style="color:var(--orange);flex-shrink:0;"></i> {{ $g }}
                            </div>
                            @endforeach
                        @endif

                        {{-- Pied de la carte avec le prix et le bouton d'action --}}
                        <div style="border-top:1px solid var(--gray-border);margin-top:1.25rem;padding-top:1.25rem;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div style="font-size:.72rem;color:var(--text-muted);">À partir de</div>
                                    <div style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:1.2rem;color:var(--orange);">
                                        {{ number_format($prix, 0, ',', ' ') }}
                                        <span style="font-size:.75rem;font-weight:400;color:var(--text-muted);">XOF/mois</span>
                                    </div>
                                </div>
                                <div style="font-size:.78rem;color:var(--text-muted);text-align:right;">
                                    Couverture<br>
                                    <strong style="color:var(--text-dark);">{{ number_format($couverture, 0, ',', ' ') }} XOF</strong>
                                </div>
                            </div>
                            {{-- Si l'utilisateur est connecté, il va direct au formulaire. Sinon, il doit s'inscrire. --}}
                            @auth
                                <a href="{{ route('client.contrats.create') }}?assurance={{ $id }}"
                                   style="display:block;text-align:center;background:var(--orange);color:white;border-radius:10px;padding:.65rem;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.875rem;text-decoration:none;transition:all .2s;"
                                   onmouseover="this.style.background='var(--orange-dark)'"
                                   onmouseout="this.style.background='var(--orange)'">
                                    Souscrire maintenant
                                </a>
                            @else
                                <a href="{{ route('register') }}"
                                   style="display:block;text-align:center;background:var(--orange);color:white;border-radius:10px;padding:.65rem;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.875rem;text-decoration:none;">
                                    S'inscrire pour souscrire
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @endif
        @endforeach

    </div>
</section>

@endsection

@push('scripts')
<script>
/**
 * Cette fonction JavaScript permet de filtrer en live les assurances.
 * Quand on clique sur "Auto", on cache tout sauf les sections Auto. Simple et efficace !
 */
function filterType(type) {
    // 1. On réinitialise tous les boutons (fond blanc, texte gris)
    document.querySelectorAll('[id^="btn-"]').forEach(btn => {
        btn.style.background = 'white';
        btn.style.color = 'var(--text-muted)';
        btn.style.borderColor = 'var(--gray-border)';
    });
    // 2. On met en couleur le bouton sur lequel on a cliqué
    const active = document.getElementById('btn-' + type);
    if (active) {
        active.style.background = 'var(--orange)';
        active.style.color = 'white';
        active.style.borderColor = 'var(--orange)';
    }
    // 3. On affiche ou on cache les sections correspondantes
    document.querySelectorAll('.type-section').forEach(s => {
        s.style.display = (type === 'all' || s.dataset.type === type) ? 'block' : 'none';
    });
}
</script>
@endpush
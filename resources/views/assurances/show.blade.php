@extends('layouts.app')
@section('title', $assurance->nom)

@section('content')

{{-- Hero --}}
<section style="background:linear-gradient(135deg,#FFF8F5,white); padding:4rem 0 2rem;">
    <div class="container">
        <a href="{{ route('assurances.index') }}"
           style="display:inline-flex; align-items:center; gap:.4rem; color:var(--text-muted); font-size:.875rem; font-weight:600; text-decoration:none; margin-bottom:1.5rem;"
           onmouseover="this.style.color='var(--orange)'" onmouseout="this.style.color='var(--text-muted)'">
            <i class="bi bi-arrow-left"></i> Retour aux offres
        </a>

        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <div style="display:inline-flex; align-items:center; gap:.5rem; background:var(--orange-light); color:var(--orange); padding:.4rem 1rem; border-radius:20px; font-weight:700; font-size:.82rem; margin-bottom:1rem;">
                    {{ $assurance->badge_type }}
                </div>
                <h1 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:2.5rem; margin-bottom:.75rem;">
                    {{ $assurance->nom }}
                </h1>
                <p style="color:var(--text-muted); font-size:1.05rem; line-height:1.7; max-width:520px;">
                    {{ $assurance->description }}
                </p>
            </div>
            <div class="col-lg-5">
                <div style="background:white; border:1.5px solid var(--gray-border); border-radius:24px; padding:2rem; box-shadow:0 20px 60px rgba(0,0,0,.06);">
                    <div style="font-size:.75rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; margin-bottom:.4rem;">À partir de</div>
                    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:900; font-size:2.5rem; color:var(--orange); line-height:1;">
                        {{ number_format($assurance->prix_mensuel, 0, ',', ' ') }}
                        <span style="font-size:1rem; font-weight:400; color:var(--text-muted);">XOF/mois</span>
                    </div>
                    <div style="margin:.75rem 0; font-size:.875rem; color:var(--text-muted);">
                        ou <strong style="color:var(--text-dark);">{{ number_format($assurance->prix_annuel, 0, ',', ' ') }} XOF/an</strong>
                        <span style="background:#D1FAE5; color:#059669; font-size:.72rem; font-weight:700; padding:.2rem .5rem; border-radius:6px; margin-left:.4rem;">-10%</span>
                    </div>
                    <div style="background:var(--gray-bg); border-radius:12px; padding:.875rem 1rem; margin:1rem 0; font-size:.875rem;">
                        Couverture jusqu'à
                        <strong style="color:var(--text-dark); display:block; font-family:'Plus Jakarta Sans',sans-serif; font-size:1.1rem; font-weight:800; margin-top:.2rem;">
                            {{ number_format($assurance->montant_couverture, 0, ',', ' ') }} XOF
                        </strong>
                    </div>
                    @auth
                        <a href="{{ route('client.contrats.create') }}?assurance={{ $assurance->id }}"
                           style="display:block; text-align:center; background:var(--orange); color:white; padding:.85rem; border-radius:12px; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:.95rem; text-decoration:none; transition:all .2s;"
                           onmouseover="this.style.background='var(--orange-dark)'"
                           onmouseout="this.style.background='var(--orange)'">
                            Souscrire maintenant
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           style="display:block; text-align:center; background:var(--orange); color:white; padding:.85rem; border-radius:12px; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:.95rem; text-decoration:none;">
                            Créer un compte pour souscrire
                        </a>
                        <a href="{{ route('login') }}"
                           style="display:block; text-align:center; margin-top:.75rem; padding:.75rem; border:1.5px solid var(--gray-border); color:var(--text-muted); border-radius:12px; font-weight:600; font-size:.875rem; text-decoration:none;">
                            Déjà membre ? Se connecter
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Garanties --}}
@if($assurance->garanties && count($assurance->garanties) > 0)
<section style="padding:4rem 0; background:white;">
    <div class="container">
        <h2 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.75rem; margin-bottom:2rem;">
            Ce que couvre cette assurance
        </h2>
        <div class="row g-3">
            @foreach($assurance->garanties as $g)
            <div class="col-md-6">
                <div style="display:flex; align-items:center; gap:.875rem; padding:1rem 1.25rem; border:1.5px solid var(--gray-border); border-radius:14px; background:white; transition:all .2s;"
                     onmouseover="this.style.borderColor='var(--orange)';this.style.background='var(--orange-light)'"
                     onmouseout="this.style.borderColor='var(--gray-border)';this.style.background='white'">
                    <div style="width:36px; height:36px; background:var(--orange-light); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="bi bi-check2-circle" style="color:var(--orange); font-size:1.1rem;"></i>
                    </div>
                    <span style="font-weight:600; font-size:.9rem;">{{ $g }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA bas de page --}}
<section style="padding:4rem 0; background:linear-gradient(135deg,#FF6B2B,#E55A1F);">
    <div class="container text-center">
        <h2 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.75rem; color:white; margin-bottom:1rem;">
            Prêt à vous protéger ?
        </h2>
        <p style="color:rgba(255,255,255,.85); margin-bottom:2rem; font-size:.95rem;">
            Souscrivez en ligne en quelques minutes
        </p>
        @auth
            <a href="{{ route('client.contrats.create') }}?assurance={{ $assurance->id }}"
               style="display:inline-block; background:white; color:var(--orange); padding:.85rem 2rem; border-radius:12px; font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1rem; text-decoration:none;">
                Souscrire maintenant
            </a>
        @else
            <a href="{{ route('register') }}"
               style="display:inline-block; background:white; color:var(--orange); padding:.85rem 2rem; border-radius:12px; font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1rem; text-decoration:none; margin-right:.75rem;">
                Créer un compte
            </a>
            <a href="{{ route('assurances.index') }}"
               style="display:inline-block; border:2px solid rgba(255,255,255,.6); color:white; padding:.85rem 2rem; border-radius:12px; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:1rem; text-decoration:none;">
                Voir toutes les offres
            </a>
        @endauth
    </div>
</section>

@endsection
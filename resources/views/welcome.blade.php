@extends('layouts.app')
@section('title', 'Accueil')

@section('content')

{{-- ── HERO ─────────────────────────────────────────────────── --}}
<section style="background: linear-gradient(135deg, #FFF8F5 0%, #FFFFFF 60%); padding: 5rem 0 4rem; overflow:hidden; position:relative;">
    <div style="position:absolute; top:-100px; right:-100px; width:500px; height:500px; border-radius:50%; background:rgba(255,107,43,.06);"></div>
    <div style="position:absolute; bottom:-50px; left:-80px; width:300px; height:300px; border-radius:50%; background:rgba(255,107,43,.04);"></div>

    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-md-6">
                <span style="background:var(--orange-light); color:var(--orange); font-size:.78rem; font-weight:700; padding:.35rem .9rem; border-radius:20px; text-transform:uppercase; letter-spacing:.06em;">
                    🇸🇳 N°1 au Sénégal
                </span>
                <h1 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:3rem; line-height:1.15; margin-top:1rem; color:var(--text-dark);">
                    Protégez ce qui<br>vous est
                    <span style="color:var(--orange); position:relative;">
                        cher.
                        <svg style="position:absolute; bottom:-8px; left:0; width:100%;" viewBox="0 0 120 12" fill="none">
                            <path d="M2 9 Q60 2 118 9" stroke="#FF6B2B" stroke-width="3" stroke-linecap="round" fill="none"/>
                        </svg>
                    </span>
                </h1>
                <p style="color:var(--text-muted); font-size:1.05rem; line-height:1.7; margin: 1.5rem 0 2rem;">
                    Sunu Karangué vous offre des solutions d'assurance simples, accessibles et adaptées à votre vie. Auto, Habitat ou Vie — souscrivez en quelques minutes.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('assurances.index') }}" class="btn btn-orange btn-lg"
                       style="background:var(--orange); color:white; border:none; border-radius:12px; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; padding:.85rem 2rem;">
                        Découvrir nos offres
                    </a>
                    <a href="{{ route('register') }}"
                       style="border:2px solid var(--orange); color:var(--orange); border-radius:12px; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; padding:.8rem 2rem; text-decoration:none; display:inline-flex; align-items:center; transition:all .2s;"
                       onmouseover="this.style.background='var(--orange)';this.style.color='white'"
                       onmouseout="this.style.background='transparent';this.style.color='var(--orange)'">
                        S'inscrire gratuitement
                    </a>
                </div>

                {{-- Chiffres clés --}}
                <div class="d-flex gap-4 mt-4 pt-3" style="border-top:1px solid var(--gray-border);">
                    <div>
                        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.4rem; color:var(--text-dark);">4 800+</div>
                        <div style="font-size:.78rem; color:var(--text-muted);">Clients assurés</div>
                    </div>
                    <div style="width:1px; background:var(--gray-border);"></div>
                    <div>
                        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.4rem; color:var(--text-dark);">14</div>
                        <div style="font-size:.78rem; color:var(--text-muted);">Agences au Sénégal</div>
                    </div>
                    <div style="width:1px; background:var(--gray-border);"></div>
                    <div>
                        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.4rem; color:var(--text-dark);">98%</div>
                        <div style="font-size:.78rem; color:var(--text-muted);">Satisfaction client</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 d-none d-md-flex justify-content-center">
                <div style="position:relative; width:420px; height:360px;">
                    {{-- Card flottante principale --}}
                    <div style="background:white; border-radius:20px; padding:1.5rem; box-shadow:0 20px 60px rgba(0,0,0,.1); position:absolute; top:0; left:0; right:0;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width:44px; height:44px; background:var(--orange-light); border-radius:12px; display:flex; align-items:center; justify-content:center; color:var(--orange); font-size:1.3rem;">🚗</div>
                            <div>
                                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:.95rem;">Assurance Auto Premium</div>
                                <div style="font-size:.78rem; color:var(--text-muted);">Tous risques • Actif</div>
                            </div>
                            <span style="margin-left:auto; background:#F0FFF4; color:#38A169; font-size:.7rem; font-weight:700; padding:.3rem .7rem; border-radius:20px;">Actif</span>
                        </div>
                        <div style="background:#F8F9FA; border-radius:12px; padding:1rem;">
                            <div class="d-flex justify-content-between mb-2">
                                <span style="font-size:.8rem; color:var(--text-muted);">Prime mensuelle</span>
                                <span style="font-weight:700; color:var(--orange);">25 000 XOF</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span style="font-size:.8rem; color:var(--text-muted);">Couverture</span>
                                <span style="font-weight:600; font-size:.8rem;">5 000 000 XOF</span>
                            </div>
                        </div>
                    </div>

                    {{-- Badge flottant --}}
                    <div style="position:absolute; bottom:20px; right:-20px; background:white; border-radius:14px; padding:.75rem 1rem; box-shadow:0 8px 30px rgba(0,0,0,.12); display:flex; align-items:center; gap:.6rem;">
                        <div style="width:36px; height:36px; background:#F0FFF4; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#38A169; font-size:1.1rem;">✓</div>
                        <div>
                            <div style="font-size:.75rem; font-weight:700;">Sinistre traité</div>
                            <div style="font-size:.7rem; color:var(--text-muted);">En 48h chrono</div>
                        </div>
                    </div>

                    {{-- Paiement badge --}}
                    <div style="position:absolute; bottom:80px; left:-30px; background:var(--orange); border-radius:14px; padding:.75rem 1rem; box-shadow:0 8px 30px rgba(255,107,43,.3); display:flex; align-items:center; gap:.6rem;">
                        <span style="font-size:1.3rem;">📱</span>
                        <div>
                            <div style="font-size:.75rem; font-weight:700; color:white;">Wave & Orange Money</div>
                            <div style="font-size:.7rem; color:rgba(255,255,255,.7);">Paiement mobile</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── NOS SOLUTIONS ────────────────────────────────────────── --}}
<section style="padding:5rem 0; background:white;" id="offres">
    <div class="container">
        <div class="text-center mb-5">
            <span style="background:var(--orange-light); color:var(--orange); font-size:.78rem; font-weight:700; padding:.35rem .9rem; border-radius:20px;">Nos Solutions</span>
            <h2 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:2.2rem; margin-top:.75rem;">
                Des offres pour chaque besoin
            </h2>
            <p style="color:var(--text-muted); max-width:500px; margin:0 auto;">
                Choisissez la couverture adaptée à votre situation et souscrivez en quelques clics.
            </p>
        </div>

        <div class="row g-4">
            {{-- Auto --}}
            <div class="col-md-4">
                <div style="border:1.5px solid var(--gray-border); border-radius:20px; padding:2rem; transition:all .3s; height:100%;"
                     onmouseover="this.style.borderColor='var(--orange)';this.style.boxShadow='0 12px 40px rgba(255,107,43,.12)';this.style.transform='translateY(-4px)'"
                     onmouseout="this.style.borderColor='var(--gray-border)';this.style.boxShadow='none';this.style.transform='none'">
                    <div style="width:56px; height:56px; background:var(--orange-light); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; margin-bottom:1.25rem;">🚗</div>
                    <h4 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:.5rem;">Assurance Auto</h4>
                    <p style="color:var(--text-muted); font-size:.875rem; line-height:1.6; margin-bottom:1.5rem;">
                        Protégez votre véhicule contre tous les risques. Formules Tiers, Tiers étendu ou Tous risques.
                    </p>
                    <ul style="list-style:none; padding:0; margin-bottom:1.5rem;">
                        @foreach(['Responsabilité civile', 'Assistance 24/7', 'Protection juridique', 'Bris de glace'] as $g)
                        <li style="font-size:.82rem; color:var(--text-muted); display:flex; align-items:center; gap:.5rem; margin-bottom:.4rem;">
                            <i class="bi bi-check-circle-fill" style="color:var(--orange); font-size:.8rem;"></i> {{ $g }}
                        </li>
                        @endforeach
                    </ul>
                    <div style="border-top:1px solid var(--gray-border); padding-top:1rem; display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <div style="font-size:.75rem; color:var(--text-muted);">À partir de</div>
                            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.2rem; color:var(--orange);">15 000 XOF<span style="font-size:.7rem; font-weight:400; color:var(--text-muted);">/mois</span></div>
                        </div>
                        <a href="{{ route('assurances.index') }}"
                           style="background:var(--orange); color:white; border-radius:10px; padding:.5rem 1rem; font-size:.82rem; font-weight:600; text-decoration:none;">
                            Souscrire
                        </a>
                    </div>
                </div>
            </div>

            {{-- Habitat --}}
            <div class="col-md-4">
                <div style="border:1.5px solid var(--orange); border-radius:20px; padding:2rem; background:linear-gradient(135deg, #FFF8F5, white); box-shadow:0 12px 40px rgba(255,107,43,.12); height:100%; position:relative; transform:translateY(-4px);">
                    <span style="position:absolute; top:-12px; left:50%; transform:translateX(-50%); background:var(--orange); color:white; font-size:.72rem; font-weight:700; padding:.25rem .9rem; border-radius:20px; white-space:nowrap;">⭐ Le plus populaire</span>
                    <div style="width:56px; height:56px; background:var(--orange-light); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; margin-bottom:1.25rem;">🏠</div>
                    <h4 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:.5rem;">Assurance Habitat</h4>
                    <p style="color:var(--text-muted); font-size:.875rem; line-height:1.6; margin-bottom:1.5rem;">
                        Votre maison et vos biens protégés contre l'incendie, le vol et les dégâts des eaux.
                    </p>
                    <ul style="list-style:none; padding:0; margin-bottom:1.5rem;">
                        @foreach(['Incendie & explosion', 'Vol et cambriolage', 'Dégât des eaux', 'Catastrophes naturelles'] as $g)
                        <li style="font-size:.82rem; color:var(--text-muted); display:flex; align-items:center; gap:.5rem; margin-bottom:.4rem;">
                            <i class="bi bi-check-circle-fill" style="color:var(--orange); font-size:.8rem;"></i> {{ $g }}
                        </li>
                        @endforeach
                    </ul>
                    <div style="border-top:1px solid rgba(255,107,43,.2); padding-top:1rem; display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <div style="font-size:.75rem; color:var(--text-muted);">À partir de</div>
                            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.2rem; color:var(--orange);">8 000 XOF<span style="font-size:.7rem; font-weight:400; color:var(--text-muted);">/mois</span></div>
                        </div>
                        <a href="{{ route('assurances.index') }}"
                           style="background:var(--orange); color:white; border-radius:10px; padding:.5rem 1rem; font-size:.82rem; font-weight:600; text-decoration:none;">
                            Souscrire
                        </a>
                    </div>
                </div>
            </div>

            {{-- Vie --}}
            <div class="col-md-4">
                <div style="border:1.5px solid var(--gray-border); border-radius:20px; padding:2rem; transition:all .3s; height:100%;"
                     onmouseover="this.style.borderColor='var(--orange)';this.style.boxShadow='0 12px 40px rgba(255,107,43,.12)';this.style.transform='translateY(-4px)'"
                     onmouseout="this.style.borderColor='var(--gray-border)';this.style.boxShadow='none';this.style.transform='none'">
                    <div style="width:56px; height:56px; background:var(--orange-light); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; margin-bottom:1.25rem;">❤️</div>
                    <h4 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:.5rem;">Assurance Vie</h4>
                    <p style="color:var(--text-muted); font-size:.875rem; line-height:1.6; margin-bottom:1.5rem;">
                        Sécurisez l'avenir de vos proches avec un capital garanti en cas de décès ou d'invalidité.
                    </p>
                    <ul style="list-style:none; padding:0; margin-bottom:1.5rem;">
                        @foreach(['Décès toutes causes', 'Invalidité totale', 'Capital garanti', 'Épargne progressive'] as $g)
                        <li style="font-size:.82rem; color:var(--text-muted); display:flex; align-items:center; gap:.5rem; margin-bottom:.4rem;">
                            <i class="bi bi-check-circle-fill" style="color:var(--orange); font-size:.8rem;"></i> {{ $g }}
                        </li>
                        @endforeach
                    </ul>
                    <div style="border-top:1px solid var(--gray-border); padding-top:1rem; display:flex; align-items:center; justify-content:space-between;">
                        <div>
                            <div style="font-size:.75rem; color:var(--text-muted);">À partir de</div>
                            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.2rem; color:var(--orange);">12 000 XOF<span style="font-size:.7rem; font-weight:400; color:var(--text-muted);">/mois</span></div>
                        </div>
                        <a href="{{ route('assurances.index') }}"
                           style="background:var(--orange); color:white; border-radius:10px; padding:.5rem 1rem; font-size:.82rem; font-weight:600; text-decoration:none;">
                            Souscrire
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── POURQUOI NOUS ────────────────────────────────────────── --}}
<section style="padding:5rem 0; background:#F8F9FA;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-md-5">
                <span style="background:var(--orange-light); color:var(--orange); font-size:.78rem; font-weight:700; padding:.35rem .9rem; border-radius:20px;">Pourquoi nous choisir ?</span>
                <h2 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:2rem; margin-top:.75rem; margin-bottom:1rem;">
                    Simple, rapide et <span style="color:var(--orange);">100% en ligne</span>
                </h2>
                <p style="color:var(--text-muted); line-height:1.7;">
                    Finis les paperasses et les longues files d'attente. Gérez toute votre assurance depuis votre téléphone ou ordinateur.
                </p>
                <a href="{{ route('register') }}"
                   style="display:inline-block; margin-top:1.5rem; background:var(--dark); color:white; border-radius:12px; padding:.8rem 1.75rem; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; text-decoration:none; transition:all .2s;"
                   onmouseover="this.style.background='var(--orange)'"
                   onmouseout="this.style.background='var(--dark)'">
                    Commencer maintenant →
                </a>
            </div>
            <div class="col-md-7">
                <div class="row g-3">
                    @foreach([
                        ['bi-lightning-charge', '#FFF8F5', 'var(--orange)', 'Souscription rapide', 'Obtenez votre couverture en moins de 5 minutes depuis n\'importe où.'],
                        ['bi-phone', '#F0FFF4', '#38A169', 'Paiement mobile', 'Payez facilement via Wave ou Orange Money, sans carte bancaire.'],
                        ['bi-headset', '#EBF8FF', '#3182CE', 'Support 24/7', 'Notre équipe est disponible à toute heure pour vous aider.'],
                        ['bi-shield-check', '#FAF5FF', '#805AD5', 'Couverture complète', 'Des garanties adaptées à chaque situation et budget.'],
                    ] as [$icon, $bg, $color, $title, $desc])
                    <div class="col-6">
                        <div style="background:white; border-radius:16px; padding:1.25rem; box-shadow:0 2px 12px rgba(0,0,0,.05);">
                            <div style="width:44px; height:44px; background:{{ $bg }}; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:.875rem;">
                                <i class="bi {{ $icon }}" style="color:{{ $color }}; font-size:1.2rem;"></i>
                            </div>
                            <h6 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:.35rem; font-size:.9rem;">{{ $title }}</h6>
                            <p style="font-size:.78rem; color:var(--text-muted); margin:0; line-height:1.5;">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA FINAL ────────────────────────────────────────────── --}}
<section style="padding:5rem 0; background:var(--dark);" id="contact">
    <div class="container text-center">
        <h2 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:2.2rem; color:white; margin-bottom:1rem;">
            Prêt à sécuriser votre avenir<br>avec <span style="color:var(--orange);">SunuKarangué</span> ?
        </h2>
        <p style="color:rgba(255,255,255,.6); max-width:480px; margin:0 auto 2rem; line-height:1.7;">
            Rejoignez des milliers de Sénégalais qui nous font confiance. Inscription gratuite, sans engagement.
        </p>
        <div class="d-flex justify-content-center flex-wrap gap-3">
            <a href="{{ route('register') }}"
               style="background:var(--orange); color:white; border-radius:12px; padding:.9rem 2rem; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; text-decoration:none; font-size:1rem; transition:all .2s;"
               onmouseover="this.style.background='var(--orange-dark)'"
               onmouseout="this.style.background='var(--orange)'">
                Créer mon compte gratuitement
            </a>
            <a href="{{ route('assurances.index') }}"
               style="background:rgba(255,255,255,.1); color:white; border-radius:12px; padding:.9rem 2rem; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; text-decoration:none; font-size:1rem; border:1.5px solid rgba(255,255,255,.2); transition:all .2s;"
               onmouseover="this.style.background='rgba(255,255,255,.2)'"
               onmouseout="this.style.background='rgba(255,255,255,.1)'">
                Voir nos offres
            </a>
        </div>
    </div>
</section>

@endsection
@extends('layouts.app')
@section('title', 'Nos Offres d\'Assurance')

@section('content')
<section style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%); padding: 5rem 0;">
    <div class="container text-center mb-5">
        <h1 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:2.5rem; margin-bottom:1rem;">Nos Offres d'Assurance</h1>
        <p style="color:var(--text-muted); max-width:600px; margin:0 auto;">
            Découvrez nos solutions adaptées à vos besoins et protégez ce qui compte vraiment pour vous.
        </p>
    </div>

    <div class="container">
        <div class="row g-4">
            @forelse($assurances as $assurance)
                <div class="col-md-4">
                    <div style="border:1.5px solid var(--gray-border); border-radius:20px; padding:2rem; background:white; transition:all .3s; height:100%; position:relative;"
                         onmouseover="this.style.borderColor='var(--orange)';this.style.boxShadow='0 12px 40px rgba(255,107,43,.12)';this.style.transform='translateY(-5px)'"
                         onmouseout="this.style.borderColor='var(--gray-border)';this.style.boxShadow='none';this.style.transform='none'">
                        
                        <div style="width:56px; height:56px; background:var(--orange-light); border-radius:16px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; margin-bottom:1.25rem;">
                            @if($assurance->type === 'auto') 🚗 @elseif($assurance->type === 'habitat') 🏠 @else ❤️ @endif
                        </div>

                        <h4 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:.5rem;">{{ $assurance->nom }}</h4>
                        <p style="color:var(--text-muted); font-size:.875rem; line-height:1.6; margin-bottom:1.5rem;">
                            {{ $assurance->description }}
                        </p>

                        <div style="border-top:1px solid var(--gray-border); padding-top:1rem; display:flex; align-items:center; justify-content:space-between; margin-top:auto;">
                            <div>
                                <div style="font-size:.75rem; color:var(--text-muted);">À partir de</div>
                                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.2rem; color:var(--orange);">
                                    {{ number_format($assurance->prix_mensuel, 0, ',', ' ') }} XOF<span style="font-size:.7rem; font-weight:400; color:var(--text-muted);">/mois</span>
                                </div>
                            </div>
                            @auth
                                @if(Auth::user()->isClient())
                                    <a href="{{ route('client.contrats.create', ['assurance_id' => $assurance->id]) }}"
                                       style="background:var(--orange); color:white; border-radius:10px; padding:.5rem 1rem; font-size:.82rem; font-weight:600; text-decoration:none;">
                                        Souscrire
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('register') }}"
                                   style="background:var(--orange); color:white; border-radius:10px; padding:.5rem 1rem; font-size:.82rem; font-weight:600; text-decoration:none;">
                                    Souscrire
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p style="color:var(--text-muted);">Aucune offre disponible pour le moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
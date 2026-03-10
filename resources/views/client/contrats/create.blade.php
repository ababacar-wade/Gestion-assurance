@extends('layouts.dashboard')
@section('title', 'Souscrire une assurance')
@section('page-title', 'Nouvelle Souscription')
@section('page-subtitle', 'Choisissez votre assurance et souscrivez en quelques étapes')

@section('content')

<div class="mb-3">
    <a href="{{ route('client.contrats.index') }}"
       style="color:var(--text-muted); font-size:.875rem; text-decoration:none; font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

{{-- Stepper --}}
<div class="d-flex align-items-center gap-0 mb-4" id="stepper">
    @foreach(['Choisir', 'Détails', 'Paiement'] as $i => $step)
    <div style="display:flex; align-items:center; flex:1;">
        <div style="display:flex; align-items:center; gap:.5rem;">
            <div style="width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center;
                        font-weight:700; font-size:.82rem; transition:all .3s;
                        {{ $i === 0 ? 'background:var(--orange); color:white;' : 'background:#E2E8F0; color:var(--text-muted);' }}"
                 id="step-{{ $i }}">
                {{ $i + 1 }}
            </div>
            <span style="font-size:.82rem; font-weight:600; color:{{ $i === 0 ? 'var(--text-dark)' : 'var(--text-muted)' }};">
                {{ $step }}
            </span>
        </div>
        @if($i < 2)
        <div style="flex:1; height:2px; margin:0 .75rem; background:#E2E8F0;" id="line-{{ $i }}"></div>
        @endif
    </div>
    @endforeach
</div>

<form method="POST" action="{{ route('client.contrats.store') }}" id="formSouscription">
    @csrf

    {{-- ÉTAPE 1 : Choisir l'assurance --}}
    <div id="etape-0">
        <div class="row g-3 mb-4">
            @forelse($assurances as $assurance)
            <div class="col-md-4">
                <label style="cursor:pointer; display:block; height:100%;">
                    <input type="radio" name="assurance_id" value="{{ $assurance->id }}"
                           style="display:none;" class="assurance-radio">
                    <div class="assurance-card" style="border:2px solid var(--gray-border); border-radius:16px; padding:1.5rem; height:100%; transition:all .2s;">
                        <div style="width:48px; height:48px; background:var(--orange-light); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; margin-bottom:1rem;">
                            {{ $assurance->type === 'auto' ? '🚗' : ($assurance->type === 'habitat' ? '🏠' : '❤️') }}
                        </div>
                        <h6 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:.4rem;">
                            {{ $assurance->nom }}
                        </h6>
                        <p style="font-size:.8rem; color:var(--text-muted); margin-bottom:1rem; line-height:1.5;">
                            {{ Str::limit($assurance->description, 80) }}
                        </p>
                        @if($assurance->garanties)
                            @foreach(array_slice($assurance->garanties, 0, 3) as $g)
                                <div style="font-size:.78rem; color:var(--text-muted); display:flex; align-items:center; gap:.4rem; margin-bottom:.3rem;">
                                    <i class="bi bi-check2" style="color:var(--orange);"></i> {{ $g }}
                                </div>
                            @endforeach
                        @endif
                        <div style="border-top:1px solid var(--gray-border); margin-top:1rem; padding-top:.875rem; display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <div style="font-size:.7rem; color:var(--text-muted);">Mensuel</div>
                                <div style="font-weight:800; color:var(--orange); font-family:'Plus Jakarta Sans',sans-serif;">
                                    {{ number_format($assurance->prix_mensuel, 0, ',', ' ') }} XOF
                                </div>
                            </div>
                            <div style="width:24px; height:24px; border-radius:50%; border:2px solid var(--gray-border); transition:all .2s;" class="check-circle"></div>
                        </div>
                    </div>
                </label>
            </div>
            @empty
                <div class="col-12 text-center py-5" style="color:var(--text-muted);">
                    Aucune assurance disponible pour le moment.
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-end">
            <button type="button" class="btn-orange btn" onclick="nextStep(0)">
                Continuer <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    {{-- ÉTAPE 2 : Détails du contrat --}}
    <div id="etape-1" style="display:none;">
        <div class="card-sunu card">
            <div class="card-header"><i class="bi bi-calendar3 me-2 text-warning"></i>Paramètres du contrat</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Date de début
                        </label>
                        <input type="date" name="date_debut" class="form-control"
                               min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Périodicité de paiement
                        </label>
                        <select name="periodicite" class="form-select" id="periodicite" onchange="calculerPrime()">
                            <option value="mensuel">Mensuel</option>
                            <option value="trimestriel">Trimestriel (−5%)</option>
                            <option value="annuel">Annuel (−10%)</option>
                        </select>
                    </div>
                </div>

                {{-- Récapitulatif prime --}}
                <div class="mt-4 p-3" style="background:var(--orange-light); border-radius:12px; border:1px solid rgba(255,107,43,.2);">
                    <div class="d-flex align-items-center justify-content-between">
                        <span style="font-size:.875rem; font-weight:600; color:var(--text-dark);">
                            <i class="bi bi-calculator me-2" style="color:var(--orange);"></i>Prime estimée
                        </span>
                        <span id="primeAffichee" style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:1.3rem; color:var(--orange);">
                            — XOF
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)" style="border-radius:10px;">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </button>
            <button type="button" class="btn-orange btn" onclick="nextStep(1)">
                Continuer <i class="bi bi-arrow-right ms-1"></i>
            </button>
        </div>
    </div>

    {{-- ÉTAPE 3 : Récap + soumettre --}}
    <div id="etape-2" style="display:none;">
        <div class="card-sunu card mb-3">
            <div class="card-header"><i class="bi bi-check2-square me-2 text-warning"></i>Récapitulatif</div>
            <div class="card-body">
                <div id="recap" style="font-size:.875rem; color:var(--text-muted);">
                    Chargement du récapitulatif...
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)" style="border-radius:10px;">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </button>
            <button type="submit" class="btn-orange btn">
                <i class="bi bi-shield-check me-1"></i> Confirmer la souscription
            </button>
        </div>
    </div>

</form>

@endsection

@push('scripts')
<script>
    let selectedAssurance = null;
    const prices = @json($assurances->pluck('prix_mensuel', 'id'));
    const names  = @json($assurances->pluck('nom', 'id'));

    // Sélection d'une assurance
    document.querySelectorAll('.assurance-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            selectedAssurance = this.value;
            document.querySelectorAll('.assurance-card').forEach(c => {
                c.style.borderColor = 'var(--gray-border)';
                c.style.background  = 'white';
                c.querySelector('.check-circle').style.background = 'transparent';
                c.querySelector('.check-circle').style.borderColor = 'var(--gray-border)';
            });
            const card = this.closest('label').querySelector('.assurance-card');
            card.style.borderColor = 'var(--orange)';
            card.style.background  = 'var(--orange-light)';
            card.querySelector('.check-circle').style.background  = 'var(--orange)';
            card.querySelector('.check-circle').style.borderColor = 'var(--orange)';
            calculerPrime();
        });
    });

    function calculerPrime() {
        if (!selectedAssurance) return;
        const base       = prices[selectedAssurance] || 0;
        const periodicite = document.querySelector('[name="periodicite"]')?.value || 'mensuel';
        let prime = base;
        if (periodicite === 'trimestriel') prime = base * 3 * 0.95;
        if (periodicite === 'annuel')      prime = base * 12 * 0.9;
        document.getElementById('primeAffichee').textContent =
            new Intl.NumberFormat('fr-FR').format(Math.round(prime)) + ' XOF';
    }

    function nextStep(current) {
        if (current === 0 && !selectedAssurance) {
            alert('Veuillez sélectionner une assurance.');
            return;
        }
        document.getElementById('etape-' + current).style.display = 'none';
        document.getElementById('etape-' + (current + 1)).style.display = 'block';

        // Mettre à jour stepper
        document.getElementById('step-' + current).style.background = '#38A169';
        document.getElementById('step-' + (current + 1)).style.background = 'var(--orange)';
        document.getElementById('step-' + (current + 1)).style.color = 'white';
        if (document.getElementById('line-' + current)) {
            document.getElementById('line-' + current).style.background = '#38A169';
        }

        if (current === 1) buildRecap();
    }

    function prevStep(current) {
        document.getElementById('etape-' + current).style.display = 'none';
        document.getElementById('etape-' + (current - 1)).style.display = 'block';
        document.getElementById('step-' + current).style.background = '#E2E8F0';
        document.getElementById('step-' + current).style.color = 'var(--text-muted)';
    }

    function buildRecap() {
        const name  = names[selectedAssurance] || '—';
        const peri  = document.querySelector('[name="periodicite"]')?.value;
        const debut = document.querySelector('[name="date_debut"]')?.value;
        const prime = document.getElementById('primeAffichee').textContent;

        document.getElementById('recap').innerHTML = `
            <div class="row g-3">
                <div class="col-6"><div style="color:var(--text-muted);font-size:.78rem;margin-bottom:.2rem;">Assurance</div><div style="font-weight:700;">${name}</div></div>
                <div class="col-6"><div style="color:var(--text-muted);font-size:.78rem;margin-bottom:.2rem;">Périodicité</div><div style="font-weight:700;">${peri}</div></div>
                <div class="col-6"><div style="color:var(--text-muted);font-size:.78rem;margin-bottom:.2rem;">Date de début</div><div style="font-weight:700;">${debut}</div></div>
                <div class="col-6"><div style="color:var(--text-muted);font-size:.78rem;margin-bottom:.2rem;">Prime</div><div style="font-weight:700;color:var(--orange);font-size:1.1rem;">${prime}</div></div>
            </div>`;
    }
</script>
@endpush
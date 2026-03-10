@extends('layouts.dashboard')
@section('title', 'Contrat ' . $contrat->numero_contrat)
@section('page-title', 'Détail du Contrat')
@section('page-subtitle', $contrat->numero_contrat)

@section('content')

<div class="mb-3">
    <a href="{{ route('client.contrats.index') }}"
       style="color:var(--text-muted); font-size:.875rem; text-decoration:none; font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Retour à mes contrats
    </a>
</div>

<div class="row g-3">

    {{-- Colonne principale --}}
    <div class="col-md-8">

        {{-- Header contrat --}}
        <div class="card-sunu card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:52px; height:52px; background:var(--orange-light); border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem;">
                            {{ $contrat->assurance->type === 'auto' ? '🚗' : ($contrat->assurance->type === 'habitat' ? '🏠' : '❤️') }}
                        </div>
                        <div>
                            <h5 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin:0;">
                                {{ $contrat->assurance->nom ?? 'Assurance' }}
                            </h5>
                            <span style="font-size:.8rem; color:var(--text-muted);">
                                {{ $contrat->numero_contrat }}
                            </span>
                        </div>
                    </div>
                    @php
                        $map = ['actif'=>'badge-actif','en_attente'=>'badge-attente','suspendu'=>'badge-suspendu','resilié'=>'badge-resilié','expire'=>'badge-expire'];
                    @endphp
                    <span class="badge-statut {{ $map[$contrat->statut] ?? '' }}" style="font-size:.82rem;">
                        {{ ucfirst(str_replace('_',' ',$contrat->statut)) }}
                    </span>
                </div>

                <hr style="border-color:var(--gray-border); margin:1.25rem 0;">

                <div class="row g-3">
                    @foreach([
                        ['Date de début',   $contrat->date_debut?->format('d/m/Y')],
                        ['Date de fin',     $contrat->date_fin?->format('d/m/Y')],
                        ['Périodicité',     ucfirst($contrat->periodicite)],
                        ['Franchise',       number_format($contrat->franchise, 0, ',', ' ') . ' XOF'],
                    ] as [$label, $val])
                    <div class="col-6 col-md-3">
                        <div style="font-size:.75rem; color:var(--text-muted); margin-bottom:.2rem;">{{ $label }}</div>
                        <div style="font-weight:600; font-size:.875rem;">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>

                @if($contrat->notes)
                    <div class="mt-3 p-3" style="background:#F8F9FA; border-radius:10px; font-size:.82rem; color:var(--text-muted);">
                        <i class="bi bi-info-circle me-1"></i> {{ $contrat->notes }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Historique paiements --}}
        <div class="card-sunu card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-credit-card me-2 text-warning"></i>Historique des paiements</span>
                @if($contrat->statut === 'en_attente')
                    <a href="#paiement" class="btn-orange btn btn-sm">Payer maintenant</a>
                @endif
            </div>
            <div class="card-body p-0">
                @if($contrat->payments->isEmpty())
                    <div class="text-center py-4" style="color:var(--text-muted); font-size:.875rem;">
                        Aucun paiement enregistré
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sunu mb-0">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Méthode</th>
                                    <th>Montant</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contrat->payments as $payment)
                                <tr>
                                    <td style="font-size:.8rem; font-weight:600;">{{ $payment->reference }}</td>
                                    <td>
                                        <span style="font-size:.8rem;">
                                            {{ match($payment->methode) {
                                                'wave' => '🔵 Wave',
                                                'orange_money' => '🟠 Orange Money',
                                                'simulation' => '🧪 Simulation',
                                                default => $payment->methode
                                            } }}
                                        </span>
                                    </td>
                                    <td style="font-weight:700; font-size:.82rem;">
                                        {{ number_format($payment->montant, 0, ',', ' ') }} XOF
                                    </td>
                                    <td style="font-size:.8rem;">
                                        {{ $payment->paye_le?->format('d/m/Y H:i') ?? '—' }}
                                    </td>
                                    <td>
                                        @php $pm = ['succes'=>'badge-actif','en_attente'=>'badge-attente','echec'=>'badge-resilié','rembourse'=>'badge-instruct']; @endphp
                                        <span class="badge-statut {{ $pm[$payment->statut] ?? '' }}">
                                            {{ ucfirst($payment->statut) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sinistres liés --}}
        <div class="card-sunu card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Sinistres liés</span>
                <a href="{{ route('client.sinistres.create') }}" class="btn btn-sm"
                   style="background:#F4F6F9; border-radius:8px; font-size:.78rem; font-weight:600;">
                    + Déclarer
                </a>
            </div>
            <div class="card-body p-0">
                @if($contrat->sinistres->isEmpty())
                    <div class="text-center py-4" style="color:var(--text-muted); font-size:.875rem;">
                        <i class="bi bi-shield-check" style="font-size:1.5rem; color:#38A169; display:block; margin-bottom:.5rem;"></i>
                        Aucun sinistre déclaré — Tout va bien !
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sunu mb-0">
                            <thead>
                                <tr><th>N°</th><th>Date</th><th>Description</th><th>Statut</th></tr>
                            </thead>
                            <tbody>
                                @foreach($contrat->sinistres as $s)
                                <tr>
                                    <td style="font-size:.8rem; font-weight:600;">{{ $s->numero_sinistre }}</td>
                                    <td style="font-size:.8rem;">{{ $s->date_sinistre?->format('d/m/Y') }}</td>
                                    <td style="font-size:.8rem;">{{ Str::limit($s->description, 50) }}</td>
                                    <td>
                                        @php $sm = ['declare'=>'badge-declare','en_instruction'=>'badge-instruct','accepte'=>'badge-accepte','refuse'=>'badge-refuse','indemnise'=>'badge-indemnise']; @endphp
                                        <span class="badge-statut {{ $sm[$s->statut] ?? '' }}">
                                            {{ ucfirst(str_replace('_',' ',$s->statut)) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Colonne droite --}}
    <div class="col-md-4">

        {{-- Prime --}}
        <div class="card-sunu card mb-3" style="background:var(--dark);">
            <div class="card-body text-center">
                <p style="color:rgba(255,255,255,.5); font-size:.78rem; margin-bottom:.25rem;">Prime à payer</p>
                <h2 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; color:var(--orange); font-size:2rem; margin-bottom:.25rem;">
                    {{ number_format($contrat->prime, 0, ',', ' ') }}
                    <span style="font-size:.9rem; color:rgba(255,255,255,.5);">XOF</span>
                </h2>
                <p style="color:rgba(255,255,255,.4); font-size:.78rem; margin-bottom:1.25rem;">
                    / {{ $contrat->periodicite }}
                </p>
                @if($contrat->statut === 'en_attente')
                    <button type="button" class="btn-orange btn w-100" data-bs-toggle="modal" data-bs-target="#modalPaiement">
                        <i class="bi bi-credit-card me-1"></i> Payer maintenant
                    </button>
                @endif
            </div>
        </div>

        {{-- Assurance agent --}}
        @if($contrat->agent)
        <div class="card-sunu card mb-3">
            <div class="card-header"><i class="bi bi-person-badge me-2 text-warning"></i>Votre agent</div>
            <div class="card-body d-flex align-items-center gap-3">
                <div style="width:44px; height:44px; background:var(--orange-light); border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:700; color:var(--orange);">
                    {{ strtoupper(substr($contrat->agent->prenom,0,1)) }}{{ strtoupper(substr($contrat->agent->nom,0,1)) }}
                </div>
                <div>
                    <div style="font-weight:600; font-size:.875rem;">{{ $contrat->agent->nom_complet }}</div>
                    <div style="font-size:.78rem; color:var(--text-muted);">{{ $contrat->agent->telephone ?? '—' }}</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Couverture --}}
        <div class="card-sunu card">
            <div class="card-header"><i class="bi bi-shield me-2 text-warning"></i>Couverture</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span style="font-size:.82rem; color:var(--text-muted);">Montant max.</span>
                    <span style="font-weight:700; font-size:.875rem;">
                        {{ number_format($contrat->assurance->montant_couverture ?? 0, 0, ',', ' ') }} XOF
                    </span>
                </div>
                @if($contrat->assurance->garanties)
                    <div style="font-size:.75rem; color:var(--text-muted); margin-top:.75rem; margin-bottom:.4rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em;">Garanties incluses</div>
                    @foreach($contrat->assurance->garanties as $g)
                        <div style="font-size:.8rem; display:flex; align-items:center; gap:.5rem; margin-bottom:.3rem;">
                            <i class="bi bi-check-circle-fill" style="color:var(--orange); font-size:.75rem;"></i>
                            {{ $g }}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Modal Paiement --}}
@if($contrat->statut === 'en_attente')
<div class="modal fade" id="modalPaiement" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;">
                    Procéder au paiement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3 p-3" style="background:var(--orange-light); border-radius:12px;">
                    <div style="font-size:1.5rem; font-weight:800; color:var(--orange);">
                        {{ number_format($contrat->prime, 0, ',', ' ') }} XOF
                    </div>
                    <div style="font-size:.8rem; color:var(--text-muted);">{{ $contrat->numero_contrat }}</div>
                </div>

                <form method="POST" action="{{ route('client.payments.store') }}">
                    @csrf
                    <input type="hidden" name="contrat_id" value="{{ $contrat->id }}">

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Méthode de paiement
                        </label>
                        <div class="d-flex flex-column gap-2">
                            @foreach(['simulation' => '🧪 Simulation (test)', 'wave' => '🔵 Wave', 'orange_money' => '🟠 Orange Money'] as $val => $label)
                            <label style="border:1.5px solid var(--gray-border); border-radius:10px; padding:.75rem; cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:.75rem;"
                                   onclick="this.parentElement.querySelectorAll('label').forEach(l=>l.style.borderColor='var(--gray-border)'); this.style.borderColor='var(--orange)'">
                                <input type="radio" name="methode" value="{{ $val }}" {{ $val === 'simulation' ? 'checked' : '' }} style="display:none;">
                                <span style="font-size:.875rem;">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3" id="champTel">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase;">
                            Numéro de téléphone
                        </label>
                        <input type="text" name="numero_payeur" class="form-control" placeholder="77 000 00 00">
                    </div>

                    <button type="submit" class="btn-orange btn w-100">
                        <i class="bi bi-lock me-1"></i> Confirmer le paiement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
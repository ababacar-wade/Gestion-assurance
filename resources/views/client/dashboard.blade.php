@extends('layouts.dashboard')
@section('title', 'Mon Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Bonjour ' . Auth::user()->prenom . ', bienvenue sur votre espace 👋')

@section('content')

{{-- ── Stats cards ─────────────────────────────────────────── --}}
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <div class="stat-value">{{ $contrats->count() }}</div>
                <div class="stat-label">Contrats</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-shield-check"></i></div>
            <div>
                <div class="stat-value">{{ $contratsActifs->count() }}</div>
                <div class="stat-label">Actifs</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="stat-value">{{ $sinistresEnCours }}</div>
                <div class="stat-label">Sinistres</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-credit-card"></i></div>
            <div>
                <div class="stat-value">
                    {{ number_format($contratsActifs->sum('prime'), 0, ',', ' ') }}
                </div>
                <div class="stat-label">Prime/mois (XOF)</div>
            </div>
        </div>
    </div>

</div>

<div class="row g-3">

    {{-- ── Mes contrats ────────────────────────────────────── --}}
    <div class="col-md-8">
        <div class="card-sunu card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-file-earmark-text me-2 text-warning"></i>Mes Contrats</span>
                <a href="{{ route('client.contrats.create') }}" class="btn-orange btn btn-sm">
                    <i class="bi bi-plus me-1"></i> Souscrire
                </a>
            </div>
            <div class="card-body p-0">
                @if($contrats->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-file-earmark-plus" style="font-size:2.5rem; color:#CBD5E0;"></i>
                        <p class="mt-2 mb-3" style="color:var(--text-muted); font-size:.875rem;">
                            Vous n'avez pas encore de contrat
                        </p>
                        <a href="{{ route('client.contrats.create') }}" class="btn-orange btn btn-sm">
                            Souscrire ma première assurance
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sunu mb-0">
                            <thead>
                                <tr>
                                    <th>Contrat</th>
                                    <th>Type</th>
                                    <th>Prime</th>
                                    <th>Échéance</th>
                                    <th>Statut</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contrats->take(5) as $contrat)
                                <tr>
                                    <td>
                                        <div style="font-weight:600; font-size:.82rem;">
                                            {{ $contrat->numero_contrat }}
                                        </div>
                                        <div style="font-size:.75rem; color:var(--text-muted);">
                                            {{ $contrat->assurance->nom ?? '—' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span style="font-size:.8rem;">
                                            {{ $contrat->assurance->badge_type ?? '—' }}
                                        </span>
                                    </td>
                                    <td style="font-weight:600;">
                                        {{ number_format($contrat->prime, 0, ',', ' ') }} XOF
                                    </td>
                                    <td style="font-size:.82rem;">
                                        {{ $contrat->date_fin?->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @php
                                            $badgeMap = [
                                                'actif'      => 'badge-actif',
                                                'en_attente' => 'badge-attente',
                                                'suspendu'   => 'badge-suspendu',
                                                'resilié'    => 'badge-resilié',
                                                'expire'     => 'badge-expire',
                                            ];
                                        @endphp
                                        <span class="badge-statut {{ $badgeMap[$contrat->statut] ?? '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $contrat->statut)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('client.contrats.show', $contrat) }}"
                                           class="btn btn-sm" style="background:#F7FAFC; font-size:.75rem; border-radius:8px;">
                                            Voir
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($contrats->count() > 5)
                        <div class="text-center py-2 border-top">
                            <a href="{{ route('client.contrats.index') }}"
                               style="font-size:.8rem; color:var(--orange); text-decoration:none; font-weight:600;">
                                Voir tous les contrats →
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- ── Colonne droite ──────────────────────────────────── --}}
    <div class="col-md-4">

        {{-- Prochain paiement --}}
        @if($prochainPaiement ?? null)
        <div class="card-sunu card mb-3">
            <div class="card-header">
                <i class="bi bi-calendar-check me-2 text-warning"></i>Prochain Paiement
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span style="font-size:.82rem; color:var(--text-muted);">Montant</span>
                    <span style="font-weight:700; color:var(--orange); font-size:1.1rem;">
                        {{ number_format($prochainPaiement->prime, 0, ',', ' ') }} XOF
                    </span>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span style="font-size:.82rem; color:var(--text-muted);">Échéance</span>
                    <span style="font-size:.82rem; font-weight:600;">
                        {{ $prochainPaiement->date_fin?->format('d/m/Y') }}
                    </span>
                </div>
                <a href="{{ route('client.payments.index') }}" class="btn-orange btn w-100 btn-sm">
                    Payer maintenant
                </a>
            </div>
        </div>
        @endif

        {{-- Actions rapides --}}
        <div class="card-sunu card">
            <div class="card-header">
                <i class="bi bi-lightning me-2 text-warning"></i>Actions rapides
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('client.contrats.create') }}"
                   class="btn-outline-orange btn btn-sm text-start">
                    <i class="bi bi-plus-circle me-2"></i>Souscrire une assurance
                </a>
                <a href="{{ route('client.sinistres.create') }}"
                   class="btn btn-sm text-start"
                   style="border:1.5px solid #E2E8F0; border-radius:10px; font-weight:600; font-size:.875rem;">
                    <i class="bi bi-exclamation-triangle me-2"></i>Déclarer un sinistre
                </a>
                <a href="{{ route('client.payments.index') }}"
                   class="btn btn-sm text-start"
                   style="border:1.5px solid #E2E8F0; border-radius:10px; font-weight:600; font-size:.875rem;">
                    <i class="bi bi-credit-card me-2"></i>Historique paiements
                </a>
                <a href="{{ route('profil') }}"
                   class="btn btn-sm text-start"
                   style="border:1.5px solid #E2E8F0; border-radius:10px; font-weight:600; font-size:.875rem;">
                    <i class="bi bi-person me-2"></i>Mon profil
                </a>
            </div>
        </div>

    </div>
</div>

@endsection
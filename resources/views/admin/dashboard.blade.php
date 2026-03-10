@extends('layouts.dashboard')
@section('title', 'Administration')
@section('page-title', 'Tableau de bord Admin')
@section('page-subtitle', 'Vue d\'ensemble de la plateforme')

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_clients'] }}</div>
                <div class="stat-label">Clients</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-person-badge"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_agents'] }}</div>
                <div class="stat-label">Agents</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-file-earmark-check"></i></div>
            <div>
                <div class="stat-value">{{ $stats['contrats_actifs'] }}</div>
                <div class="stat-label">Contrats actifs</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="stat-value">{{ $stats['sinistres_ouverts'] }}</div>
                <div class="stat-label">Sinistres ouverts</div>
            </div>
        </div>
    </div>
</div>

{{-- Revenus du mois --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card-sunu card p-4" style="background:var(--dark); border-radius:16px;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p style="color:rgba(255,255,255,.6); font-size:.82rem; margin-bottom:.25rem;">
                        Revenus du mois en cours
                    </p>
                    <h2 style="color:white; font-size:2rem; font-weight:800; margin:0;">
                        {{ number_format($stats['revenus_mois'], 0, ',', ' ') }}
                        <span style="font-size:1rem; font-weight:500; color:rgba(255,255,255,.5);">XOF</span>
                    </h2>
                </div>
                <div style="background:rgba(255,107,43,.2); border-radius:14px; width:60px; height:60px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-graph-up-arrow" style="font-size:1.6rem; color:var(--orange);"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- Derniers contrats --}}
    <div class="col-md-7">
        <div class="card-sunu card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-earmark-text me-2 text-warning"></i>Derniers Contrats</span>
                <a href="{{ route('admin.contrats.index') }}"
                   style="font-size:.8rem; color:var(--orange); text-decoration:none; font-weight:600;">
                    Voir tout →
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sunu mb-0">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Assurance</th>
                                <th>Prime</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($derniersContrats as $contrat)
                            <tr>
                                <td>
                                    <div style="font-weight:600; font-size:.82rem;">
                                        {{ $contrat->client->nom_complet ?? '—' }}
                                    </div>
                                </td>
                                <td style="font-size:.82rem;">
                                    {{ $contrat->assurance->badge_type ?? '—' }}
                                </td>
                                <td style="font-weight:600; font-size:.82rem;">
                                    {{ number_format($contrat->prime, 0, ',', ' ') }} XOF
                                </td>
                                <td>
                                    @php
                                        $map = ['actif'=>'badge-actif','en_attente'=>'badge-attente','suspendu'=>'badge-suspendu','resilié'=>'badge-resilié'];
                                    @endphp
                                    <span class="badge-statut {{ $map[$contrat->statut] ?? '' }}">
                                        {{ ucfirst(str_replace('_',' ',$contrat->statut)) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4" style="color:var(--text-muted);">Aucun contrat</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Derniers sinistres --}}
    <div class="col-md-5">
        <div class="card-sunu card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Sinistres récents</span>
                <a href="{{ route('admin.sinistres.index') }}"
                   style="font-size:.8rem; color:var(--orange); text-decoration:none; font-weight:600;">
                    Voir tout →
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($derniersSinistres as $sinistre)
                <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                    <div style="width:36px; height:36px; border-radius:10px; background:#FFF5F5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="bi bi-exclamation-triangle" style="color:#E53E3E;"></i>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div style="font-size:.82rem; font-weight:600;">
                            {{ $sinistre->numero_sinistre }}
                        </div>
                        <div style="font-size:.75rem; color:var(--text-muted);">
                            {{ $sinistre->client->nom_complet ?? '—' }}
                        </div>
                    </div>
                    @php
                        $sm = ['declare'=>'badge-declare','en_instruction'=>'badge-instruct','accepte'=>'badge-accepte','refuse'=>'badge-refuse','indemnise'=>'badge-indemnise'];
                    @endphp
                    <span class="badge-statut {{ $sm[$sinistre->statut] ?? '' }}" style="font-size:.65rem;">
                        {{ ucfirst(str_replace('_',' ',$sinistre->statut)) }}
                    </span>
                </div>
                @empty
                <div class="text-center py-4" style="color:var(--text-muted); font-size:.875rem;">
                    Aucun sinistre récent
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection
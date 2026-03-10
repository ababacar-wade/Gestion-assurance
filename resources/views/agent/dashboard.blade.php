@extends('layouts.dashboard')
@section('title', 'Espace Agent')
@section('page-title', 'Tableau de bord Agent')
@section('page-subtitle', 'Gérez vos contrats et sinistres')

@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <div class="stat-value">{{ $contrats->count() }}</div>
                <div class="stat-label">Mes contrats</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-shield-check"></i></div>
            <div>
                <div class="stat-value">{{ $contratsActifs }}</div>
                <div class="stat-label">Contrats actifs</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="stat-value">{{ $sinistresEnCours }}</div>
                <div class="stat-label">Sinistres à traiter</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-value">{{ $nbClients }}</div>
                <div class="stat-label">Mes clients</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12">
        <div class="card-sunu card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-earmark-text me-2 text-warning"></i>Mes Contrats récents</span>
                <a href="{{ route('agent.contrats.index') }}"
                   style="font-size:.8rem; color:var(--orange); text-decoration:none; font-weight:600;">
                    Voir tout →
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sunu mb-0">
                        <thead>
                            <tr>
                                <th>N° Contrat</th>
                                <th>Client</th>
                                <th>Assurance</th>
                                <th>Prime</th>
                                <th>Statut</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contrats->take(8) as $contrat)
                            <tr>
                                <td style="font-size:.82rem; font-weight:600;">{{ $contrat->numero_contrat }}</td>
                                <td style="font-size:.82rem;">{{ $contrat->client->nom_complet ?? '—' }}</td>
                                <td style="font-size:.82rem;">{{ $contrat->assurance->badge_type ?? '—' }}</td>
                                <td style="font-weight:600; font-size:.82rem;">{{ number_format($contrat->prime, 0, ',', ' ') }} XOF</td>
                                <td>
                                    @php $map = ['actif'=>'badge-actif','en_attente'=>'badge-attente','suspendu'=>'badge-suspendu']; @endphp
                                    <span class="badge-statut {{ $map[$contrat->statut] ?? 'badge-expire' }}">
                                        {{ ucfirst(str_replace('_',' ',$contrat->statut)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('agent.contrats.show', $contrat) }}"
                                       class="btn btn-sm" style="background:#F7FAFC; font-size:.75rem; border-radius:8px;">
                                        Voir
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr><td colspan="6" class="text-center py-4" style="color:var(--text-muted);">Aucun contrat assigné</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@extends('layouts.dashboard')
@section('title', 'Gestion des Contrats')
@section('page-title', 'Gestion des Contrats')
@section('page-subtitle', 'Tous les contrats de la plateforme')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div class="d-flex gap-2 flex-wrap">
        @foreach(['tous'=>'Tous','actif'=>'Actifs','en_attente'=>'En attente','suspendu'=>'Suspendus','resilié'=>'Résiliés'] as $val => $label)
        <a href="{{ request()->fullUrlWithQuery(['statut' => $val]) }}"
           style="padding:.4rem .9rem; border-radius:20px; font-size:.8rem; font-weight:600; text-decoration:none; transition:all .2s;
                  {{ request('statut','tous') === $val ? 'background:var(--orange); color:white;' : 'background:#F4F6F9; color:var(--text-muted);' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>
    <div style="font-size:.82rem; color:var(--text-muted);">
        Total : <strong>{{ $contrats->total() }}</strong>
    </div>
</div>

<div class="card-sunu card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sunu mb-0">
                <thead>
                    <tr>
                        <th>N° Contrat</th>
                        <th>Client</th>
                        <th>Assurance</th>
                        <th>Agent</th>
                        <th>Prime</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contrats as $contrat)
                    <tr>
                        <td style="font-weight:700; font-size:.82rem;">{{ $contrat->numero_contrat }}</td>
                        <td>
                            <div style="font-size:.82rem; font-weight:600;">{{ $contrat->client->nom_complet ?? '—' }}</div>
                        </td>
                        <td style="font-size:.82rem;">{{ $contrat->assurance->badge_type ?? '—' }}</td>
                        <td style="font-size:.82rem;">{{ $contrat->agent->nom_complet ?? '—' }}</td>
                        <td style="font-weight:700; color:var(--orange); font-size:.82rem;">
                            {{ number_format($contrat->prime,0,',',' ') }} XOF
                        </td>
                        <td style="font-size:.8rem;">{{ $contrat->date_debut?->format('d/m/Y') }}</td>
                        <td style="font-size:.8rem;">{{ $contrat->date_fin?->format('d/m/Y') }}</td>
                        <td>
                            @php $map=['actif'=>'badge-actif','en_attente'=>'badge-attente','suspendu'=>'badge-suspendu','resilié'=>'badge-resilié','expire'=>'badge-expire']; @endphp
                            <span class="badge-statut {{ $map[$contrat->statut] ?? '' }}">
                                {{ ucfirst(str_replace('_',' ',$contrat->statut)) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.contrats.show', $contrat) }}"
                                   class="btn btn-sm" style="background:#F4F6F9; border-radius:8px; font-size:.75rem;"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.contrats.edit', $contrat) }}"
                                   class="btn btn-sm" style="background:var(--orange-light); color:var(--orange); border-radius:8px; font-size:.75rem;"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="9" class="text-center py-5" style="color:var(--text-muted);">Aucun contrat</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-center">{{ $contrats->links() }}</div>
    </div>
</div>

@endsection
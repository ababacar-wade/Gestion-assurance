@extends('layouts.dashboard')
@section('title', 'Contrats')
@section('page-title', 'Gestion des Contrats')
@section('page-subtitle', 'Contrats dont vous êtes responsable')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div class="d-flex gap-2 flex-wrap">
        @foreach(['tous' => 'Tous', 'actif' => 'Actifs', 'en_attente' => 'En attente', 'suspendu' => 'Suspendus'] as $val => $label)
        <a href="{{ request()->fullUrlWithQuery(['statut' => $val]) }}"
           style="padding:.4rem .9rem; border-radius:20px; font-size:.8rem; font-weight:600; text-decoration:none; transition:all .2s;
                  {{ request('statut', 'tous') === $val ? 'background:var(--orange); color:white;' : 'background:#F4F6F9; color:var(--text-muted);' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>
    {{-- Barre de recherche --}}
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="q" class="form-control form-control-sm"
               placeholder="N° contrat, client..." value="{{ request('q') }}"
               style="border-radius:10px; width:200px; border-color:#E2E8F0;">
        <button type="submit" class="btn-orange btn btn-sm">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>

<div class="card-sunu card">
    <div class="card-body p-0">
        @if($contrats->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-file-earmark-text" style="font-size:2.5rem; color:#CBD5E0;"></i>
                <p class="mt-2" style="color:var(--text-muted); font-size:.875rem;">Aucun contrat assigné</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sunu mb-0">
                    <thead>
                        <tr>
                            <th>N° Contrat</th>
                            <th>Client</th>
                            <th>Assurance</th>
                            <th>Périodicité</th>
                            <th>Prime</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contrats as $contrat)
                        <tr>
                            <td style="font-weight:700; font-size:.82rem;">{{ $contrat->numero_contrat }}</td>
                            <td>
                                <div style="font-size:.82rem; font-weight:600;">{{ $contrat->client->nom_complet ?? '—' }}</div>
                                <div style="font-size:.75rem; color:var(--text-muted);">{{ $contrat->client->telephone ?? '' }}</div>
                            </td>
                            <td style="font-size:.82rem;">{{ $contrat->assurance->badge_type ?? '—' }}</td>
                            <td style="font-size:.82rem;">{{ ucfirst($contrat->periodicite) }}</td>
                            <td style="font-weight:700; color:var(--orange); font-size:.82rem;">
                                {{ number_format($contrat->prime, 0, ',', ' ') }} XOF
                            </td>
                            <td style="font-size:.8rem;">{{ $contrat->date_debut?->format('d/m/Y') }}</td>
                            <td style="font-size:.8rem;">{{ $contrat->date_fin?->format('d/m/Y') }}</td>
                            <td>
                                @php $map = ['actif'=>'badge-actif','en_attente'=>'badge-attente','suspendu'=>'badge-suspendu','resilié'=>'badge-resilié','expire'=>'badge-expire']; @endphp
                                <span class="badge-statut {{ $map[$contrat->statut] ?? '' }}">
                                    {{ ucfirst(str_replace('_',' ',$contrat->statut)) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('agent.contrats.show', $contrat) }}"
                                       class="btn btn-sm" style="background:#F4F6F9; border-radius:8px; font-size:.75rem;" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('agent.contrats.edit', $contrat) }}"
                                       class="btn btn-sm" style="background:var(--orange-light); color:var(--orange); border-radius:8px; font-size:.75rem;" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-center">
                {{ $contrats->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
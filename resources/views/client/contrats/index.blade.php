@extends('layouts.dashboard')
@section('title', 'Mes Contrats')
@section('page-title', 'Mes Contrats')
@section('page-subtitle', 'Gérez toutes vos assurances')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div class="d-flex gap-2 flex-wrap">
        @foreach(['tous' => 'Tous', 'actif' => 'Actifs', 'en_attente' => 'En attente', 'expire' => 'Expirés'] as $val => $label)
        <a href="{{ request()->fullUrlWithQuery(['statut' => $val]) }}"
           style="padding:.4rem .9rem; border-radius:20px; font-size:.8rem; font-weight:600; text-decoration:none; transition:all .2s;
                  {{ request('statut', 'tous') === $val ? 'background:var(--orange); color:white;' : 'background:#F4F6F9; color:var(--text-muted);' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>
    <a href="{{ route('client.contrats.create') }}" class="btn-orange btn btn-sm">
        <i class="bi bi-plus me-1"></i> Nouveau contrat
    </a>
</div>

<div class="card-sunu card">
    <div class="card-body p-0">
        @if($contrats->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-file-earmark-plus" style="font-size:3rem; color:#CBD5E0;"></i>
                <h5 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-top:1rem;">Aucun contrat</h5>
                <p style="color:var(--text-muted); font-size:.875rem;">Souscrivez votre première assurance dès maintenant</p>
                <a href="{{ route('client.contrats.create') }}" class="btn-orange btn mt-2">
                    Souscrire une assurance
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sunu mb-0">
                    <thead>
                        <tr>
                            <th>N° Contrat</th>
                            <th>Assurance</th>
                            <th>Type</th>
                            <th>Périodicité</th>
                            <th>Prime</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contrats as $contrat)
                        <tr>
                            <td style="font-weight:700; font-size:.82rem;">{{ $contrat->numero_contrat }}</td>
                            <td style="font-size:.82rem;">{{ $contrat->assurance->nom ?? '—' }}</td>
                            <td>{{ $contrat->assurance->badge_type ?? '—' }}</td>
                            <td style="font-size:.82rem;">{{ ucfirst($contrat->periodicite) }}</td>
                            <td style="font-weight:700; color:var(--orange);">
                                {{ number_format($contrat->prime, 0, ',', ' ') }} XOF
                            </td>
                            <td style="font-size:.82rem;">{{ $contrat->date_debut?->format('d/m/Y') }}</td>
                            <td style="font-size:.82rem;">{{ $contrat->date_fin?->format('d/m/Y') }}</td>
                            <td>
                                @php
                                    $map = ['actif'=>'badge-actif','en_attente'=>'badge-attente','suspendu'=>'badge-suspendu','resilié'=>'badge-resilié','expire'=>'badge-expire'];
                                @endphp
                                <span class="badge-statut {{ $map[$contrat->statut] ?? '' }}">
                                    {{ ucfirst(str_replace('_',' ',$contrat->statut)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('client.contrats.show', $contrat) }}"
                                   class="btn btn-sm" style="background:#F4F6F9; border-radius:8px; font-size:.75rem; font-weight:600;">
                                    <i class="bi bi-eye me-1"></i>Voir
                                </a>
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
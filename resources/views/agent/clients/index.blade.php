@extends('layouts.dashboard')
@section('title', 'Mes Clients')
@section('page-title', 'Mes Clients')
@section('page-subtitle', 'Clients liés à vos contrats')

@section('content')

<div class="card-sunu card">
    <div class="card-body p-0">

        {{-- Header avec recherche --}}
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--gray-border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
            <div style="font-weight:700; font-size:.9rem;">
                {{ $clients->total() }} client(s) au total
            </div>
        </div>

        @if($clients->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-people" style="font-size:2.5rem; color:#CBD5E0;"></i>
                <p class="mt-2" style="color:var(--text-muted); font-size:.875rem;">Aucun client assigné pour l'instant</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sunu mb-0">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Téléphone</th>
                            <th>Contrats actifs</th>
                            <th>Total contrats</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $client->photo_url }}" alt="avatar"
                                         style="width:38px; height:38px; border-radius:50%; object-fit:cover;">
                                    <div>
                                        <div style="font-weight:700; font-size:.875rem;">{{ $client->nom_complet }}</div>
                                        <div style="font-size:.75rem; color:var(--text-muted);">{{ $client->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:.875rem;">{{ $client->telephone ?? '—' }}</td>
                            <td>
                                <span style="font-weight:700; color:var(--orange);">
                                    {{ $client->contrats->where('statut', 'actif')->count() }}
                                </span>
                            </td>
                            <td style="font-size:.875rem;">{{ $client->contrats->count() }}</td>
                            <td>
                                <a href="{{ route('agent.clients.show', $client) }}"
                                   style="padding:.4rem .9rem; background:var(--orange-light); color:var(--orange); border-radius:8px; font-size:.78rem; font-weight:700; text-decoration:none;">
                                    Voir →
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 d-flex justify-content-center">
                {{ $clients->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
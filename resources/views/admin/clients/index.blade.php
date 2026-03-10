@extends('layouts.dashboard')
@section('title', 'Gestion des Clients')
@section('page-title', 'Gestion des Clients')
@section('page-subtitle', 'Liste complète des clients')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="q" class="form-control form-control-sm"
               placeholder="Nom, email, téléphone..." value="{{ request('q') }}"
               style="border-radius:10px; width:240px; border-color:#E2E8F0;">
        <button type="submit" class="btn-orange btn btn-sm"><i class="bi bi-search"></i></button>
    </form>
    <div style="font-size:.82rem; color:var(--text-muted);">
        Total : <strong>{{ $clients->total() }}</strong> clients
    </div>
</div>

<div class="card-sunu card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sunu mb-0">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>CIN</th>
                        <th>Contrats</th>
                        <th>Statut</th>
                        <th>Inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:36px; height:36px; background:var(--orange-light); border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:700; color:var(--orange); font-size:.78rem; flex-shrink:0;">
                                    {{ strtoupper(substr($client->prenom,0,1)) }}{{ strtoupper(substr($client->nom,0,1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600; font-size:.875rem;">{{ $client->nom_complet }}</div>
                                    <div style="font-size:.72rem; color:var(--text-muted);">{{ $client->profession ?? 'Non renseigné' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.82rem;">{{ $client->email }}</td>
                        <td style="font-size:.82rem;">{{ $client->telephone ?? '—' }}</td>
                        <td style="font-size:.8rem; color:var(--text-muted);">{{ $client->cin ?? '—' }}</td>
                        <td>
                            <span style="background:#EBF8FF; color:#3182CE; padding:.2rem .6rem; border-radius:20px; font-size:.72rem; font-weight:700;">
                                {{ $client->contrats_count ?? $client->contrats->count() }}
                            </span>
                        </td>
                        <td>
                            @if($client->is_active)
                                <span class="badge-statut badge-actif">Actif</span>
                            @else
                                <span class="badge-statut badge-resilié">Inactif</span>
                            @endif
                        </td>
                        <td style="font-size:.78rem; color:var(--text-muted);">{{ $client->created_at?->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.clients.show', $client) }}"
                                   class="btn btn-sm" style="background:#F4F6F9; border-radius:8px; font-size:.75rem;" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.clients.edit', $client) }}"
                                   class="btn btn-sm" style="background:var(--orange-light); color:var(--orange); border-radius:8px; font-size:.75rem;" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.clients.destroy', $client) }}"
                                      onsubmit="return confirm('Supprimer ce client ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm"
                                            style="background:#FFF5F5; color:#E53E3E; border-radius:8px; font-size:.75rem;" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="8" class="text-center py-5" style="color:var(--text-muted);">Aucun client trouvé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-center">{{ $clients->links() }}</div>
    </div>
</div>

@endsection
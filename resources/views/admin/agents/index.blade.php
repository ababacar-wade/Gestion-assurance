@extends('layouts.dashboard')
@section('title', 'Gestion des Agents')
@section('page-title', 'Gestion des Agents')
@section('page-subtitle', 'Équipe commerciale')

{{-- 
    Cette page permet à l'admin de voir la liste de tous ses agents.
    On y voit leurs coordonnées et leur performance (nombre de contrats).
--}}

@section('content')

{{-- Petit bouton "plus" pour aller créer un nouvel agent --}}
<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('admin.agents.create') }}" class="btn-orange btn btn-sm">
        <i class="bi bi-plus me-1"></i> Nouvel Agent
    </a>
</div>

<div class="card-sunu card">
    <div class="card-body p-0">
        <div class="table-responsive">
            {{-- Tableau listant tous les agents --}}
            <table class="table table-sunu mb-0">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Matricule</th>
                        <th>Zone couverte</th>
                        <th>Contrats actifs</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agents as $agent)
                    <tr>
                        <td>
                            {{-- Affichage du nom avec de petites initiales à gauche pour faire joli --}}
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:36px; height:36px; background:#EBF8FF; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:700; color:#3182CE; font-size:.78rem; flex-shrink:0;">
                                    {{ strtoupper(substr($agent->prenom,0,1)) }}{{ strtoupper(substr($agent->nom,0,1)) }}
                                </div>
                                <div style="font-weight:600; font-size:.875rem;">{{ $agent->nom_complet }}</div>
                            </div>
                        </td>
                        <td style="font-size:.82rem;">{{ $agent->email }}</td>
                        <td style="font-size:.82rem;">{{ $agent->telephone ?? '—' }}</td>
                        <td style="font-size:.82rem; font-weight:600;">{{ $agent->matricule ?? '—' }}</td>
                        <td style="font-size:.82rem;">{{ $agent->zone_couverte ?? '—' }}</td>
                        <td>
                            {{-- Badge montrant le nombre de contrats gérés --}}
                            <span style="background:#F0FFF4; color:#38A169; padding:.2rem .6rem; border-radius:20px; font-size:.72rem; font-weight:700;">
                                {{ $agent->nb_contrats_actifs ?? 0 }}
                            </span>
                        </td>
                        <td>
                            {{-- Badge Vert si l'agent est actif, Rouge s'il est inactif --}}
                            @if($agent->is_active)
                                <span class="badge-statut badge-actif">Actif</span>
                            @else
                                <span class="badge-statut badge-resilié">Inactif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                {{-- Voir le profil complet --}}
                                <a href="{{ route('admin.agents.show', $agent) }}"
                                   class="btn btn-sm" style="background:#F4F6F9; border-radius:8px; font-size:.75rem;"><i class="bi bi-eye"></i></a>
                                {{-- Modifier l'agent --}}
                                <a href="{{ route('admin.agents.edit', $agent) }}"
                                   class="btn btn-sm" style="background:var(--orange-light); color:var(--orange); border-radius:8px; font-size:.75rem;"><i class="bi bi-pencil"></i></a>
                                {{-- Supprimer l'agent avec une confirmation pour éviter les bêtises --}}
                                <form method="POST" action="{{ route('admin.agents.destroy', $agent) }}"
                                    onsubmit="return confirm('Supprimer cet agent ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm"
                                            style="background:#FFF5F5; color:#E53E3E; border-radius:8px; font-size:.75rem;"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                        {{-- Si aucun agent n'est enregistré dans la base --}}
                        <tr><td colspan="8" class="text-center py-5" style="color:var(--text-muted);">Aucun agent</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Pagination si la liste est longue --}}
        <div class="p-3 d-flex justify-content-center">{{ $agents->links() }}</div>
    </div>
</div>

@endsection
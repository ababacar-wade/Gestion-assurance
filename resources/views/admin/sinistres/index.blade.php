@extends('layouts.dashboard')
@section('title', 'Gestion des Sinistres')
@section('page-title', 'Gestion des Sinistres')
@section('page-subtitle', 'Tous les sinistres déclarés')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div class="d-flex gap-2 flex-wrap">
        @foreach(['tous'=>'Tous','declare'=>'Déclarés','en_instruction'=>'En instruction','accepte'=>'Acceptés','refuse'=>'Refusés','indemnise'=>'Indemnisés'] as $val => $label)
        <a href="{{ request()->fullUrlWithQuery(['statut' => $val]) }}"
           style="padding:.4rem .9rem; border-radius:20px; font-size:.8rem; font-weight:600; text-decoration:none; transition:all .2s;
                  {{ request('statut','tous') === $val ? 'background:var(--orange); color:white;' : 'background:#F4F6F9; color:var(--text-muted);' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>
</div>

<div class="card-sunu card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sunu mb-0">
                <thead>
                    <tr>
                        <th>N° Sinistre</th>
                        <th>Client</th>
                        <th>Contrat</th>
                        <th>Agent</th>
                        <th>Date</th>
                        <th>Réclamé</th>
                        <th>Accordé</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sinistres as $s)
                    <tr>
                        <td style="font-weight:700; font-size:.82rem;">{{ $s->numero_sinistre }}</td>
                        <td>
                            <div style="font-size:.82rem; font-weight:600;">{{ $s->client->nom_complet ?? '—' }}</div>
                        </td>
                        <td style="font-size:.8rem;">{{ $s->contrat->numero_contrat ?? '—' }}</td>
                        <td style="font-size:.8rem;">{{ $s->agent->nom_complet ?? '<span style="color:var(--text-muted);">Non assigné</span>' }}</td>
                        <td style="font-size:.8rem;">{{ $s->date_sinistre?->format('d/m/Y') }}</td>
                        <td style="font-size:.82rem;">{{ $s->montant_reclame ? number_format($s->montant_reclame,0,',',' ').' XOF' : '—' }}</td>
                        <td style="font-weight:700; font-size:.82rem; color:#38A169;">{{ $s->montant_accorde ? number_format($s->montant_accorde,0,',',' ').' XOF' : '—' }}</td>
                        <td>
                            @php $sm=['declare'=>'badge-declare','en_instruction'=>'badge-instruct','accepte'=>'badge-accepte','refuse'=>'badge-refuse','indemnise'=>'badge-indemnise']; @endphp
                            <span class="badge-statut {{ $sm[$s->statut] ?? '' }}">
                                {{ ucfirst(str_replace('_',' ',$s->statut)) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.sinistres.show', $s) }}"
                                   class="btn btn-sm" style="background:#F4F6F9; border-radius:8px; font-size:.75rem;"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.sinistres.edit', $s) }}"
                                   class="btn btn-sm" style="background:var(--orange-light); color:var(--orange); border-radius:8px; font-size:.75rem;"><i class="bi bi-pencil"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="9" class="text-center py-5" style="color:var(--text-muted);">Aucun sinistre</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-center">{{ $sinistres->links() }}</div>
    </div>
</div>

@endsection
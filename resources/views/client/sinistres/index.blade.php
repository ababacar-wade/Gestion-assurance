@extends('layouts.dashboard')
@section('title', 'Mes Sinistres')
@section('page-title', 'Mes Sinistres')
@section('page-subtitle', 'Suivez vos déclarations')

@section('content')

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('client.sinistres.create') }}" class="btn-orange btn btn-sm">
        <i class="bi bi-plus me-1"></i> Déclarer un sinistre
    </a>
</div>

<div class="card-sunu card">
    <div class="card-body p-0">
        @if($sinistres->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-shield-check" style="font-size:2.5rem; color:#38A169;"></i>
                <h6 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-top:.75rem;">Aucun sinistre</h6>
                <p style="color:var(--text-muted); font-size:.875rem;">Vous n'avez aucun sinistre déclaré</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sunu mb-0">
                    <thead>
                        <tr>
                            <th>N° Sinistre</th>
                            <th>Contrat</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Montant réclamé</th>
                            <th>Statut</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sinistres as $s)
                        <tr>
                            <td style="font-weight:700; font-size:.82rem;">{{ $s->numero_sinistre }}</td>
                            <td style="font-size:.8rem;">{{ $s->contrat->numero_contrat ?? '—' }}</td>
                            <td style="font-size:.8rem;">{{ $s->date_sinistre?->format('d/m/Y') }}</td>
                            <td style="font-size:.8rem;">{{ Str::limit($s->description, 50) }}</td>
                            <td style="font-weight:600; font-size:.82rem;">
                                {{ $s->montant_reclame ? number_format($s->montant_reclame, 0, ',', ' ') . ' XOF' : '—' }}
                            </td>
                            <td>
                                @php $sm = ['declare'=>'badge-declare','en_instruction'=>'badge-instruct','accepte'=>'badge-accepte','refuse'=>'badge-refuse','indemnise'=>'badge-indemnise']; @endphp
                                <span class="badge-statut {{ $sm[$s->statut] ?? '' }}">
                                    {{ ucfirst(str_replace('_',' ',$s->statut)) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('client.sinistres.show', $s) }}"
                                   class="btn btn-sm" style="background:#F4F6F9; border-radius:8px; font-size:.75rem;">
                                    <i class="bi bi-eye me-1"></i>Voir
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-center">
                {{ $sinistres->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
@extends('layouts.dashboard')
@section('title', 'Gestion des Paiements')
@section('page-title', 'Gestion des Paiements')
@section('page-subtitle', 'Toutes les transactions')

@section('content')

{{-- Stats rapides --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-value">{{ $payments->where('statut','succes')->count() }}</div>
                <div class="stat-label">Succès</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-clock"></i></div>
            <div>
                <div class="stat-value">{{ $payments->where('statut','en_attente')->count() }}</div>
                <div class="stat-label">En attente</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-x-circle"></i></div>
            <div>
                <div class="stat-value">{{ $payments->where('statut','echec')->count() }}</div>
                <div class="stat-label">Échecs</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-currency-exchange"></i></div>
            <div>
                <div class="stat-value" style="font-size:1.1rem;">
                    {{ number_format($payments->where('statut','succes')->sum('montant'),0,',',' ') }}
                </div>
                <div class="stat-label">Total XOF perçu</div>
            </div>
        </div>
    </div>
</div>

<div class="card-sunu card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sunu mb-0">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Contrat</th>
                        <th>Méthode</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                    <tr>
                        <td style="font-weight:700; font-size:.82rem;">{{ $p->reference }}</td>
                        <td style="font-size:.82rem;">{{ $p->client->nom_complet ?? '—' }}</td>
                        <td style="font-size:.8rem;">{{ $p->contrat->numero_contrat ?? '—' }}</td>
                        <td>
                            <span style="font-size:.82rem;">
                                {{ match($p->methode) { 'wave'=>'🔵 Wave','orange_money'=>'🟠 Orange Money','simulation'=>'🧪 Simulation', default=>$p->methode } }}
                            </span>
                        </td>
                        <td style="font-weight:700; color:var(--orange); font-size:.875rem;">
                            {{ number_format($p->montant,0,',',' ') }} XOF
                        </td>
                        <td style="font-size:.8rem;">{{ $p->created_at?->format('d/m/Y H:i') }}</td>
                        <td>
                            @php $pm=['succes'=>'badge-actif','en_attente'=>'badge-attente','echec'=>'badge-resilié','rembourse'=>'badge-instruct']; @endphp
                            <span class="badge-statut {{ $pm[$p->statut] ?? '' }}">{{ ucfirst($p->statut) }}</span>
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-5" style="color:var(--text-muted);">Aucun paiement</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-center">{{ $payments->links() }}</div>
    </div>
</div>

@endsection
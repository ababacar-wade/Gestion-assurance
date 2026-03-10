{{--
    FICHIER : client/payments/index.blade.php
--}}
@extends('layouts.dashboard')
@section('title', 'Mes Paiements')
@section('page-title', 'Historique des Paiements')
@section('page-subtitle', 'Tous vos paiements effectués')

@section('content')

<div class="card-sunu card">
    <div class="card-body p-0">
        @if($paiements->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-credit-card" style="font-size:2.5rem; color:#CBD5E0;"></i>
                <p class="mt-2" style="color:var(--text-muted); font-size:.875rem;">Aucun paiement enregistré</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-sunu mb-0">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Contrat</th>
                            <th>Méthode</th>
                            <th>Montant</th>
                            <th>Période couverte</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paiements as $p)
                        <tr>
                            <td style="font-weight:700; font-size:.8rem;">{{ $p->reference }}</td>
                            <td style="font-size:.8rem;">{{ $p->contrat->numero_contrat ?? '—' }}</td>
                            <td>
                                <span style="font-size:.8rem;">
                                    {{ match($p->methode) {
                                        'wave'         => '🔵 Wave',
                                        'orange_money' => '🟠 Orange Money',
                                        'simulation'   => '🧪 Simulation',
                                        default        => $p->methode
                                    } }}
                                </span>
                            </td>
                            <td style="font-weight:700; color:var(--orange); font-size:.875rem;">
                                {{ number_format($p->montant, 0, ',', ' ') }} XOF
                            </td>
                            <td style="font-size:.78rem; color:var(--text-muted);">
                                {{ $p->periode_debut?->format('d/m/Y') }} → {{ $p->periode_fin?->format('d/m/Y') }}
                            </td>
                            <td style="font-size:.8rem;">{{ $p->paye_le?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td>
                                @php $pm = ['succes'=>'badge-actif','en_attente'=>'badge-attente','echec'=>'badge-resilié']; @endphp
                                <span class="badge-statut {{ $pm[$p->statut] ?? '' }}">
                                    {{ ucfirst($p->statut) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-center">
                {{ $paiements->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
@extends('layouts.dashboard')
@section('title', 'Paiement ' . $payment->reference)
@section('page-title', 'Détail Paiement')
@section('page-subtitle', $payment->reference)

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card-sunu card">
    <div class="card-body p-4">

        {{-- Statut central --}}
        <div class="text-center mb-4">
            @if($payment->statut === 'succes')
                <div style="width:72px;height:72px;background:#D1FAE5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:2rem;">✅</div>
                <h5 style="color:#059669;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;">Paiement réussi</h5>
            @elseif($payment->statut === 'echec')
                <div style="width:72px;height:72px;background:#FEE2E2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:2rem;">❌</div>
                <h5 style="color:#DC2626;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;">Paiement échoué</h5>
            @else
                <div style="width:72px;height:72px;background:#FEF3C7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:2rem;">⏳</div>
                <h5 style="color:#D97706;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;">En attente</h5>
            @endif
            <div style="font-size:2rem;font-family:'Plus Jakarta Sans',sans-serif;font-weight:900;color:var(--orange);margin-top:.5rem;">
                {{ number_format($payment->montant, 0, ',', ' ') }}
                <span style="font-size:1rem;font-weight:400;color:var(--text-muted);">XOF</span>
            </div>
        </div>

        <hr style="border-color:var(--gray-border);">

        {{-- Client --}}
        @if($payment->client)
        <div style="display:flex;align-items:center;gap:.75rem;padding:.75rem 0;border-bottom:1px solid var(--gray-border);">
            <img src="{{ $payment->client->photo_url }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
            <div>
                <div style="font-weight:700;font-size:.875rem;">{{ $payment->client->nom_complet }}</div>
                <div style="font-size:.75rem;color:var(--text-muted);">{{ $payment->client->email }}</div>
            </div>
            <a href="{{ route('admin.clients.show', $payment->client) }}"
               style="margin-left:auto;font-size:.78rem;color:var(--orange);text-decoration:none;font-weight:600;">
                Voir client →
            </a>
        </div>
        @endif

        {{-- Infos paiement --}}
        @foreach([
            ['Référence',       $payment->reference],
            ['Transaction ID',  $payment->transaction_id ?? '—'],
            ['Méthode',         match($payment->methode) {
                'wave'         => '🔵 Wave',
                'orange_money' => '🟠 Orange Money',
                'free_money'   => '🟢 Free Money',
                'simulation'   => '🧪 Simulation',
                default        => $payment->methode
            }],
            ['Contrat',         $payment->contrat->numero_contrat ?? '—'],
            ['Assurance',       $payment->contrat->assurance->nom ?? '—'],
            ['Période',         ($payment->periode_debut?->format('d/m/Y') ?? '—') . ' → ' . ($payment->periode_fin?->format('d/m/Y') ?? '—')],
            ['Payé le',         $payment->paye_le?->format('d/m/Y H:i') ?? '—'],
        ] as [$label, $val])
        <div style="display:flex;justify-content:space-between;align-items:center;padding:.65rem 0;border-bottom:1px solid var(--gray-border);">
            <span style="font-size:.82rem;color:var(--text-muted);">{{ $label }}</span>
            <span style="font-weight:700;font-size:.875rem;">{{ $val }}</span>
        </div>
        @endforeach

        {{-- Payload simulation --}}
        @if($payment->payload_retour)
        <div style="margin-top:1rem;background:var(--gray-bg);border-radius:12px;padding:1rem;">
            <div style="font-size:.72rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;margin-bottom:.5rem;">Données de retour</div>
            <pre style="font-size:.75rem;color:var(--text-muted);margin:0;white-space:pre-wrap;">{{ json_encode($payment->payload_retour, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
        @endif

        <div class="d-flex gap-3 mt-4">
            <a href="{{ route('admin.payments.index') }}"
               style="flex:1;text-align:center;padding:.65rem;border:1.5px solid var(--gray-border);border-radius:10px;color:var(--text-muted);font-weight:700;font-size:.875rem;text-decoration:none;">
                ← Retour
            </a>
            @if($payment->contrat)
            <a href="{{ route('admin.contrats.show', $payment->contrat) }}"
               style="flex:1;text-align:center;padding:.65rem;background:var(--orange);color:white;border-radius:10px;font-weight:700;font-size:.875rem;text-decoration:none;">
                Voir le contrat
            </a>
            @endif
        </div>
    </div>
</div>
</div>
</div>
@endsection
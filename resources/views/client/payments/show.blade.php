@extends('layouts.dashboard')
@section('title', 'Reçu ' . $payment->reference)
@section('page-title', 'Reçu de paiement')
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
                {{ number_format($payment->montant, 0, ',', ' ') }} <span style="font-size:1rem;font-weight:400;color:var(--text-muted);">XOF</span>
            </div>
        </div>

        <hr style="border-color:var(--gray-border);">

        {{-- Détails --}}
        @foreach([
            ['Référence',      $payment->reference],
            ['Transaction ID', $payment->transaction_id ?? '—'],
            ['Méthode',        match($payment->methode) { 'wave'=>'🔵 Wave','orange_money'=>'🟠 Orange Money','free_money'=>'🟢 Free Money','simulation'=>'🧪 Simulation', default=>$payment->methode }],
            ['Contrat',        $payment->contrat->numero_contrat ?? '—'],
            ['Assurance',      $payment->contrat->assurance->nom ?? '—'],
            ['Payé le',        $payment->paye_le?->format('d/m/Y H:i') ?? '—'],
        ] as [$label, $val])
        <div style="display:flex;justify-content:space-between;align-items:center;padding:.65rem 0;border-bottom:1px solid var(--gray-border);">
            <span style="font-size:.82rem;color:var(--text-muted);">{{ $label }}</span>
            <span style="font-weight:700;font-size:.875rem;">{{ $val }}</span>
        </div>
        @endforeach

        <div class="mt-4 d-flex gap-3">
            <a href="{{ route('client.payments.index') }}"
               style="flex:1;text-align:center;padding:.65rem;border:1.5px solid var(--gray-border);border-radius:10px;color:var(--text-muted);font-weight:700;font-size:.875rem;text-decoration:none;">
                ← Retour
            </a>
            <a href="{{ route('client.contrats.show', $payment->contrat_id) }}"
               style="flex:1;text-align:center;padding:.65rem;background:var(--orange);color:white;border-radius:10px;font-weight:700;font-size:.875rem;text-decoration:none;">
                Voir le contrat
            </a>
        </div>
    </div>
</div>
</div>
</div>
@endsection
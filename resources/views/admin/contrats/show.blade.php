@extends('layouts.dashboard')
@section('title', 'Contrat ' . $contrat->numero_contrat)
@section('page-title', 'Détail Contrat')
@section('page-subtitle', $contrat->numero_contrat)
@section('content')
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card-sunu card mb-4">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">Informations contrat</h6>
        @php $badges=['actif'=>['bg-success','Actif'],'en_attente'=>['bg-warning text-dark','En attente'],'suspendu'=>['bg-secondary','Suspendu'],'resilié'=>['bg-danger','Résilié'],'expire'=>['bg-dark','Expiré']]; [$bc,$bl]=$badges[$contrat->statut]??['bg-secondary',$contrat->statut]; @endphp
        <div class="text-center mb-3"><span class="badge {{ $bc }}" style="font-size:.85rem;padding:.5rem 1.25rem;border-radius:20px;">{{ $bl }}</span></div>
        @foreach([['N° Contrat',$contrat->numero_contrat],['Début',$contrat->date_debut?->format('d/m/Y')],['Fin',$contrat->date_fin?->format('d/m/Y')],['Périodicité',ucfirst($contrat->periodicite)],['Prime',number_format($contrat->prime,0,',',' ').' XOF']] as [$lb,$vl])
        <div style="display:flex;justify-content:space-between;padding:.6rem 0;border-bottom:1px solid var(--gray-border);">
            <span style="font-size:.8rem;color:var(--text-muted);">{{ $lb }}</span>
            <span style="font-weight:700;font-size:.875rem;">{{ $vl }}</span>
        </div>
        @endforeach
        <a href="{{ route('admin.contrats.edit', $contrat) }}" style="display:block;text-align:center;margin-top:1rem;padding:.6rem;background:var(--orange);color:white;border-radius:10px;font-weight:700;font-size:.82rem;text-decoration:none;">Modifier le statut</a>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="row g-4">
      <div class="col-sm-6">
        <div class="card-sunu card">
          <div class="card-body">
            <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">👤 Client</h6>
            @if($contrat->client)
            <div class="d-flex align-items-center gap-3">
                <img src="{{ $contrat->client->photo_url }}" style="width:44px;height:44px;border-radius:50%;object-fit:cover;">
                <div>
                    <div style="font-weight:700;font-size:.875rem;">{{ $contrat->client->nom_complet }}</div>
                    <div style="font-size:.78rem;color:var(--text-muted);">{{ $contrat->client->email }}</div>
                </div>
            </div>
            @else <p style="color:var(--text-muted);font-size:.875rem;">—</p> @endif
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="card-sunu card">
          <div class="card-body">
            <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">🛡️ Assurance</h6>
            @if($contrat->assurance)
            <div style="font-weight:700;font-size:.875rem;">{{ $contrat->assurance->nom }}</div>
            <div style="font-size:.78rem;color:var(--text-muted);margin-top:.25rem;">{{ $contrat->assurance->badge_type }}</div>
            @endif
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="card-sunu card">
          <div class="card-body">
            <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">Paiements ({{ $contrat->payments->count() }})</h6>
            @forelse($contrat->payments as $p)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:.65rem;background:var(--gray-bg);border-radius:10px;margin-bottom:.4rem;">
                <div>
                    <div style="font-size:.82rem;font-weight:700;">{{ $p->reference }}</div>
                    <div style="font-size:.75rem;color:var(--text-muted);">{{ $p->paye_le?->format('d/m/Y') ?? '—' }}</div>
                </div>
                <div class="text-end">
                    <div style="font-weight:700;color:var(--orange);font-size:.875rem;">{{ number_format($p->montant,0,',',' ') }} XOF</div>
                    <span class="badge {{ $p->statut==='succes'?'bg-success':($p->statut==='echec'?'bg-danger':'bg-warning text-dark') }}" style="font-size:.7rem;">{{ $p->statut }}</span>
                </div>
            </div>
            @empty <p style="color:var(--text-muted);font-size:.875rem;">Aucun paiement</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
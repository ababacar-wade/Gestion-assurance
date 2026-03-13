@extends('layouts.dashboard')
@section('title', 'Sinistre ' . $sinistre->numero_sinistre)
@section('page-title', 'Détail Sinistre')
@section('page-subtitle', $sinistre->numero_sinistre)
@section('content')
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card-sunu card mb-4">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">Résumé</h6>
        @php $badges=['declare'=>['bg-warning text-dark','Déclaré'],'en_instruction'=>['bg-info','En instruction'],'accepte'=>['bg-success','Accepté'],'refuse'=>['bg-danger','Refusé'],'indemnise'=>['bg-primary','Indemnisé']]; [$bc,$bl]=$badges[$sinistre->statut]??['bg-secondary',$sinistre->statut]; @endphp
        <div class="text-center mb-3"><span class="badge {{ $bc }}" style="font-size:.85rem;padding:.5rem 1.25rem;border-radius:20px;">{{ $bl }}</span></div>
        @foreach([['N° Sinistre',$sinistre->numero_sinistre],['Date sinistre',$sinistre->date_sinistre?->format('d/m/Y')??'—'],['Lieu',$sinistre->lieu??'—'],['Réclamé',($sinistre->montant_reclame?number_format($sinistre->montant_reclame,0,',',' ').' XOF':'—')],['Accordé',($sinistre->montant_accorde?number_format($sinistre->montant_accorde,0,',',' ').' XOF':'En attente')]] as [$lb,$vl])
        <div style="display:flex;justify-content:space-between;padding:.6rem 0;border-bottom:1px solid var(--gray-border);">
            <span style="font-size:.8rem;color:var(--text-muted);">{{ $lb }}</span>
            <span style="font-weight:700;font-size:.875rem;">{{ $vl }}</span>
        </div>
        @endforeach
        <a href="{{ route('admin.sinistres.edit', $sinistre) }}" style="display:block;text-align:center;margin-top:1rem;padding:.6rem;background:var(--orange);color:white;border-radius:10px;font-weight:700;font-size:.82rem;text-decoration:none;">Instruire le dossier</a>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card-sunu card mb-4">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">Description</h6>
        <p style="color:var(--text-muted);font-size:.9rem;line-height:1.7;margin:0;">{{ $sinistre->description }}</p>
      </div>
    </div>
    @if($sinistre->client)
    <div class="card-sunu card mb-4">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">👤 Client</h6>
        <div class="d-flex align-items-center gap-3">
            <img src="{{ $sinistre->client->photo_url }}" style="width:44px;height:44px;border-radius:50%;object-fit:cover;">
            <div>
                <div style="font-weight:700;">{{ $sinistre->client->nom_complet }}</div>
                <div style="font-size:.78rem;color:var(--text-muted);">{{ $sinistre->client->email }}</div>
            </div>
        </div>
      </div>
    </div>
    @endif
    @if($sinistre->notes_agent)
    <div class="card-sunu card">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">📝 Note de l'agent</h6>
        <div style="background:var(--gray-bg);border-radius:12px;padding:1rem;font-size:.875rem;color:var(--text-muted);line-height:1.7;">{{ $sinistre->notes_agent }}</div>
      </div>
    </div>
    @endif
  </div>
</div>
@endsection
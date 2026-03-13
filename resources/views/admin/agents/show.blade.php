@extends('layouts.dashboard')
@section('title', 'Agent ' . $agent->nom_complet)
@section('page-title', 'Fiche Agent')
@section('page-subtitle', $agent->nom_complet)
@section('content')
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card-sunu card mb-4">
      <div class="card-body text-center p-4">
        <img src="{{ $agent->photo_url }}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--orange);margin-bottom:1rem;">
        <h5 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;margin:0;">{{ $agent->nom_complet }}</h5>
        <div style="color:var(--text-muted);font-size:.82rem;margin:.25rem 0 .5rem;">{{ $agent->email }}</div>
        <div style="font-size:.78rem;background:var(--orange-light);color:var(--orange);padding:.3rem .8rem;border-radius:20px;display:inline-block;font-weight:700;">
            {{ $agent->matricule ?? 'AGT-????' }}
        </div>
        <div class="mt-2">
            <span class="badge {{ $agent->is_active ? 'bg-success' : 'bg-danger' }}" style="border-radius:20px;">{{ $agent->is_active ? 'Actif' : 'Inactif' }}</span>
        </div>
        <a href="{{ route('admin.agents.edit', $agent) }}" style="display:block;margin-top:1rem;padding:.5rem 1rem;background:var(--orange);color:white;border-radius:8px;font-size:.8rem;font-weight:700;text-decoration:none;">Modifier</a>
      </div>
    </div>
    <div class="card-sunu card mb-4">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">Informations</h6>
        @foreach([['📞','Téléphone',$agent->telephone??'—'],['📍','Zone couverte',$agent->zone_couverte??'—'],['🏠','Adresse',$agent->adresse??'—']] as [$ic,$lb,$vl])
        <div style="display:flex;gap:.75rem;align-items:center;padding:.6rem 0;border-bottom:1px solid var(--gray-border);">
            <span>{{ $ic }}</span>
            <div><div style="font-size:.7rem;color:var(--text-muted);">{{ $lb }}</div><div style="font-weight:600;font-size:.875rem;">{{ $vl }}</div></div>
        </div>
        @endforeach
        <div style="margin-top:1rem;text-align:center;">
            <div style="font-size:1.75rem;font-family:'Plus Jakarta Sans',sans-serif;font-weight:900;color:var(--orange);">{{ $agent->contrats->count() }}</div>
            <div style="font-size:.78rem;color:var(--text-muted);">Contrats gérés</div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card-sunu card mb-4">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">Contrats gérés</h6>
        @forelse($agent->contrats as $c)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:.75rem;background:var(--gray-bg);border-radius:12px;margin-bottom:.5rem;">
            <div>
                <div style="font-weight:700;font-size:.875rem;">{{ $c->numero_contrat }}</div>
                <div style="font-size:.78rem;color:var(--text-muted);">{{ $c->client->nom_complet ?? '—' }} · {{ $c->assurance->nom ?? '—' }}</div>
            </div>
            <span class="badge {{ $c->statut==='actif'?'bg-success':'bg-secondary' }}" style="font-size:.72rem;">{{ $c->statut }}</span>
        </div>
        @empty
        <p style="color:var(--text-muted);font-size:.875rem;">Aucun contrat</p>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
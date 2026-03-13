@extends('layouts.dashboard')
@section('title', 'Client ' . $client->nom_complet)
@section('page-title', 'Fiche Client')
@section('page-subtitle', $client->nom_complet)
@section('content')
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card-sunu card mb-4">
      <div class="card-body text-center p-4">
        <img src="{{ $client->photo_url }}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--orange);margin-bottom:1rem;">
        <h5 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;margin:0;">{{ $client->nom_complet }}</h5>
        <div style="color:var(--text-muted);font-size:.82rem;margin:.25rem 0 1rem;">{{ $client->email }}</div>
        <span class="badge {{ $client->is_active ? 'bg-success' : 'bg-danger' }}" style="border-radius:20px;padding:.4rem .9rem;">
            {{ $client->is_active ? 'Actif' : 'Inactif' }}
        </span>
        <div class="mt-3 d-flex gap-2 justify-content-center">
            <a href="{{ route('admin.clients.edit', $client) }}" style="padding:.5rem 1rem;background:var(--orange);color:white;border-radius:8px;font-size:.8rem;font-weight:700;text-decoration:none;">Modifier</a>
        </div>
      </div>
    </div>
    <div class="card-sunu card mb-4">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">Informations</h6>
        @foreach([['📞','Téléphone',$client->telephone??'—'],['🏠','Adresse',$client->adresse??'—'],['🎂','Naissance',$client->date_naissance?->format('d/m/Y')??'—'],['💼','Profession',$client->profession??'—'],['🪪','CIN',$client->cin??'—']] as [$ic,$lb,$vl])
        <div style="display:flex;gap:.75rem;align-items:center;padding:.6rem 0;border-bottom:1px solid var(--gray-border);">
            <span>{{ $ic }}</span>
            <div><div style="font-size:.7rem;color:var(--text-muted);">{{ $lb }}</div><div style="font-weight:600;font-size:.875rem;">{{ $vl }}</div></div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card-sunu card mb-4">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">Contrats ({{ $client->contrats->count() }})</h6>
        @forelse($client->contrats as $c)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:.75rem;background:var(--gray-bg);border-radius:12px;margin-bottom:.5rem;">
            <div>
                <div style="font-weight:700;font-size:.875rem;">{{ $c->numero_contrat }}</div>
                <div style="font-size:.78rem;color:var(--text-muted);">{{ $c->assurance->nom ?? '—' }}</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge {{ $c->statut==='actif'?'bg-success':($c->statut==='en_attente'?'bg-warning text-dark':'bg-secondary') }}" style="font-size:.72rem;">{{ $c->statut }}</span>
                <a href="{{ route('admin.contrats.show', $c) }}" style="font-size:.78rem;color:var(--orange);text-decoration:none;font-weight:600;">Voir →</a>
            </div>
        </div>
        @empty
        <p style="color:var(--text-muted);font-size:.875rem;margin:0;">Aucun contrat</p>
        @endforelse
      </div>
    </div>
    <div class="card-sunu card mb-4">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">Sinistres ({{ $client->sinistres->count() }})</h6>
        @forelse($client->sinistres as $s)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:.75rem;background:var(--gray-bg);border-radius:12px;margin-bottom:.5rem;">
            <div>
                <div style="font-weight:700;font-size:.875rem;">{{ $s->numero_sinistre }}</div>
                <div style="font-size:.78rem;color:var(--text-muted);">{{ $s->date_sinistre?->format('d/m/Y') }}</div>
            </div>
            <span class="badge bg-warning text-dark" style="font-size:.72rem;">{{ $s->statut }}</span>
        </div>
        @empty
        <p style="color:var(--text-muted);font-size:.875rem;margin:0;">Aucun sinistre</p>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
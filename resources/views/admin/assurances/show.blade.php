@extends('layouts.dashboard')
@section('title', $assurance->nom)
@section('page-title', 'Détail Assurance')
@section('page-subtitle', $assurance->nom)
@section('content')
<div class="row g-4">
  <div class="col-lg-5">
    <div class="card-sunu card mb-4">
      <div class="card-body p-4">
        <div style="display:inline-flex;align-items:center;gap:.5rem;background:var(--orange-light);color:var(--orange);padding:.4rem 1rem;border-radius:20px;font-weight:700;font-size:.82rem;margin-bottom:1rem;">
            {{ $assurance->badge_type }}
        </div>
        <h4 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;margin-bottom:.5rem;">{{ $assurance->nom }}</h4>
        <p style="color:var(--text-muted);font-size:.875rem;line-height:1.7;">{{ $assurance->description }}</p>
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('admin.assurances.edit', $assurance) }}" style="flex:1;text-align:center;padding:.6rem;background:var(--orange);color:white;border-radius:10px;font-weight:700;font-size:.82rem;text-decoration:none;">Modifier</a>
            <form method="POST" action="{{ route('admin.assurances.destroy', $assurance) }}" onsubmit="return confirm('Supprimer cette assurance ?')">
                @csrf @method('DELETE')
                <button type="submit" style="padding:.6rem 1rem;background:#FEE2E2;color:#DC2626;border:none;border-radius:10px;font-weight:700;font-size:.82rem;cursor:pointer;">Supprimer</button>
            </form>
        </div>
      </div>
    </div>
    <div class="card-sunu card">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">Tarification</h6>
        <div style="display:flex;justify-content:space-between;padding:.65rem 0;border-bottom:1px solid var(--gray-border);">
            <span style="color:var(--text-muted);font-size:.82rem;">Prix mensuel</span>
            <span style="font-weight:800;color:var(--orange);">{{ number_format($assurance->prix_mensuel,0,',',' ') }} XOF</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:.65rem 0;border-bottom:1px solid var(--gray-border);">
            <span style="color:var(--text-muted);font-size:.82rem;">Prix annuel</span>
            <span style="font-weight:700;">{{ number_format($assurance->prix_annuel,0,',',' ') }} XOF</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:.65rem 0;border-bottom:1px solid var(--gray-border);">
            <span style="color:var(--text-muted);font-size:.82rem;">Couverture max</span>
            <span style="font-weight:700;">{{ number_format($assurance->montant_couverture,0,',',' ') }} XOF</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:.65rem 0;">
            <span style="color:var(--text-muted);font-size:.82rem;">Statut</span>
            <span class="badge {{ $assurance->is_active?'bg-success':'bg-secondary' }}">{{ $assurance->is_active?'Active':'Inactive' }}</span>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-7">
    <div class="card-sunu card mb-4">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">✅ Garanties incluses</h6>
        @forelse($assurance->garanties ?? [] as $g)
        <div style="display:flex;align-items:center;gap:.75rem;padding:.5rem 0;border-bottom:1px solid var(--gray-border);">
            <i class="bi bi-check2-circle" style="color:var(--orange);flex-shrink:0;"></i>
            <span style="font-size:.875rem;">{{ $g }}</span>
        </div>
        @empty
        <p style="color:var(--text-muted);font-size:.875rem;">Aucune garantie définie</p>
        @endforelse
      </div>
    </div>
    <div class="card-sunu card">
      <div class="card-body">
        <h6 style="font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;margin-bottom:1rem;">Contrats liés ({{ $assurance->contrats->count() }})</h6>
        @forelse($assurance->contrats->take(5) as $c)
        <div style="display:flex;justify-content:space-between;align-items:center;padding:.65rem;background:var(--gray-bg);border-radius:10px;margin-bottom:.4rem;">
            <span style="font-size:.82rem;font-weight:700;">{{ $c->numero_contrat }}</span>
            <span class="badge {{ $c->statut==='actif'?'bg-success':'bg-secondary' }}" style="font-size:.7rem;">{{ $c->statut }}</span>
        </div>
        @empty
        <p style="color:var(--text-muted);font-size:.875rem;">Aucun contrat</p>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
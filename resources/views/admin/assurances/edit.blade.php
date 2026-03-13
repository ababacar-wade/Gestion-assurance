@extends('layouts.dashboard')
@section('title', 'Modifier assurance')
@section('page-title', 'Modifier l\'assurance')
@section('page-subtitle', $assurance->nom)
@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
<div class="card-sunu card">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.assurances.update', $assurance) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-12">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Nom</label>
                <input type="text" name="nom" value="{{ old('nom', $assurance->nom) }}" required
                       style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
            </div>
            <div class="col-12">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Description</label>
                <textarea name="description" rows="3" style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">{{ old('description', $assurance->description) }}</textarea>
            </div>
            <div class="col-sm-4">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Prix mensuel (XOF)</label>
                <input type="number" name="prix_mensuel" value="{{ old('prix_mensuel', $assurance->prix_mensuel) }}" required min="0"
                       style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
            </div>
            <div class="col-sm-4">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Prix annuel (XOF)</label>
                <input type="number" name="prix_annuel" value="{{ old('prix_annuel', $assurance->prix_annuel) }}" required min="0"
                       style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
            </div>
            <div class="col-sm-4">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Couverture max (XOF)</label>
                <input type="number" name="montant_couverture" value="{{ old('montant_couverture', $assurance->montant_couverture) }}" required min="0"
                       style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
            </div>
            <div class="col-12">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">
                    Garanties <span style="font-weight:400;">(une par ligne)</span>
                </label>
                <textarea name="garanties_text" rows="5" style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;font-family:monospace;">{{ old('garanties_text', is_array($assurance->garanties) ? implode("\n", $assurance->garanties) : '') }}</textarea>
            </div>
            <div class="col-sm-6">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Statut</label>
                <select name="is_active" style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
                    <option value="1" {{ $assurance->is_active?'selected':'' }}>Active</option>
                    <option value="0" {{ !$assurance->is_active?'selected':'' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="d-flex gap-3 mt-4">
            <a href="{{ route('admin.assurances.index') }}" style="flex:1;text-align:center;padding:.65rem;border:1.5px solid var(--gray-border);border-radius:10px;color:var(--text-muted);font-weight:700;font-size:.875rem;text-decoration:none;">Annuler</a>
            <button type="submit" style="flex:2;padding:.65rem;background:var(--orange);color:white;border:none;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;cursor:pointer;">Enregistrer les modifications</button>
        </div>
    </form>
  </div>
</div>
</div></div>
@endsection
@extends('layouts.dashboard')
@section('title', 'Modifier contrat')
@section('page-title', 'Modifier le contrat')
@section('page-subtitle', $contrat->numero_contrat)
@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card-sunu card">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.contrats.update', $contrat) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Statut du contrat</label>
            <select name="statut" style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
                @foreach(['en_attente'=>'En attente','actif'=>'Actif','suspendu'=>'Suspendu','resilié'=>'Résilié','expire'=>'Expiré'] as $val => $label)
                <option value="{{ $val }}" {{ $contrat->statut===$val?'selected':'' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Notes internes</label>
            <textarea name="notes" rows="4" style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">{{ old('notes', $contrat->notes) }}</textarea>
        </div>
        <div class="d-flex gap-3">
            <a href="{{ route('admin.contrats.show', $contrat) }}" style="flex:1;text-align:center;padding:.65rem;border:1.5px solid var(--gray-border);border-radius:10px;color:var(--text-muted);font-weight:700;font-size:.875rem;text-decoration:none;">Annuler</a>
            <button type="submit" style="flex:2;padding:.65rem;background:var(--orange);color:white;border:none;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;cursor:pointer;">Enregistrer</button>
        </div>
    </form>
  </div>
</div>
</div></div>
@endsection
@extends('layouts.dashboard')
@section('title', 'Modifier client')
@section('page-title', 'Modifier le client')
@section('page-subtitle', $client->nom_complet)
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card-sunu card">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.clients.update', $client) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-sm-6">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Nom</label>
                <input type="text" name="nom" value="{{ old('nom', $client->nom) }}" required
                       style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
                @error('nom')<div style="color:#DC2626;font-size:.78rem;margin-top:.25rem;">{{ $message }}</div>@enderror
            </div>
            <div class="col-sm-6">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Prénom</label>
                <input type="text" name="prenom" value="{{ old('prenom', $client->prenom) }}" required
                       style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
            </div>
            <div class="col-sm-6">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Téléphone</label>
                <input type="text" name="telephone" value="{{ old('telephone', $client->telephone) }}"
                       style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
            </div>
            <div class="col-sm-6">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Statut</label>
                <select name="is_active" style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
                    <option value="1" {{ $client->is_active ? 'selected' : '' }}>Actif</option>
                    <option value="0" {{ !$client->is_active ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div class="col-12">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Adresse</label>
                <input type="text" name="adresse" value="{{ old('adresse', $client->adresse) }}"
                       style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
            </div>
        </div>
        <div class="d-flex gap-3 mt-4">
            <a href="{{ route('admin.clients.show', $client) }}" style="flex:1;text-align:center;padding:.65rem;border:1.5px solid var(--gray-border);border-radius:10px;color:var(--text-muted);font-weight:700;font-size:.875rem;text-decoration:none;">Annuler</a>
            <button type="submit" style="flex:2;padding:.65rem;background:var(--orange);color:white;border:none;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.875rem;cursor:pointer;">Enregistrer</button>
        </div>
    </form>
  </div>
</div>
</div></div>
@endsection
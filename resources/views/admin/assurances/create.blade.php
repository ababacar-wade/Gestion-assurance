@extends('layouts.dashboard')
@section('title', 'Créer une Assurance')
@section('page-title', 'Nouvelle Offre d\'Assurance')
@section('page-subtitle', 'Ajouter une offre au catalogue')

@section('content')

<div class="mb-3">
    <a href="{{ route('admin.assurances.index') }}"
       style="color:var(--text-muted); font-size:.875rem; text-decoration:none; font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row g-3 justify-content-center">
    <div class="col-md-8">
        <div class="card-sunu card">
            <div class="card-header"><i class="bi bi-shield-plus me-2 text-warning"></i>Informations de l'offre</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.assurances.store') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                                Type <span style="color:var(--orange);">*</span>
                            </label>
                            <select name="type" class="form-select" id="typeSelect" onchange="toggleFields()" required>
                                <option value="">-- Choisir --</option>
                                <option value="auto"    {{ old('type')==='auto'    ? 'selected' : '' }}>🚗 Auto</option>
                                <option value="habitat" {{ old('type')==='habitat' ? 'selected' : '' }}>🏠 Habitat</option>
                                <option value="vie"     {{ old('type')==='vie'     ? 'selected' : '' }}>❤️ Vie</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                                Nom de l'offre <span style="color:var(--orange);">*</span>
                            </label>
                            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom') }}" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                                Prix mensuel (XOF) <span style="color:var(--orange);">*</span>
                            </label>
                            <input type="number" name="prix_mensuel" class="form-control"
                                   value="{{ old('prix_mensuel') }}" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                                Prix annuel (XOF) <span style="color:var(--orange);">*</span>
                            </label>
                            <input type="number" name="prix_annuel" class="form-control"
                                   value="{{ old('prix_annuel') }}" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                                Couverture max (XOF) <span style="color:var(--orange);">*</span>
                            </label>
                            <input type="number" name="montant_couverture" class="form-control"
                                   value="{{ old('montant_couverture') }}" min="0" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Garanties incluses (une par ligne)
                        </label>
                        <textarea name="garanties_text" class="form-control" rows="4"
                                  placeholder="Responsabilité civile&#10;Assistance 24/7&#10;Protection juridique">{{ old('garanties_text') }}</textarea>
                        <small style="color:var(--text-muted); font-size:.75rem;">Chaque ligne devient une garantie séparée</small>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive" style="font-weight:600; font-size:.875rem;">
                                Offre active (visible dans le catalogue)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('admin.assurances.index') }}"
                           class="btn btn-outline-secondary" style="border-radius:10px;">Annuler</a>
                        <button type="submit" class="btn-orange btn">
                            <i class="bi bi-plus me-1"></i> Créer l'offre
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
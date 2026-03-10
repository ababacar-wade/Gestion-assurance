@extends('layouts.dashboard')
@section('title', 'Déclarer un Sinistre')
@section('page-title', 'Déclarer un Sinistre')
@section('page-subtitle', 'Remplissez le formulaire de déclaration')

@section('content')

<div class="mb-3">
    <a href="{{ route('client.sinistres.index') }}"
       style="color:var(--text-muted); font-size:.875rem; text-decoration:none; font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card-sunu card">
            <div class="card-header">
                <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Informations du sinistre
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('client.sinistres.store') }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Contrat concerné <span style="color:var(--orange);">*</span>
                        </label>
                        <select name="contrat_id" class="form-select" required>
                            <option value="">-- Sélectionnez un contrat --</option>
                            @foreach($contrats as $contrat)
                                <option value="{{ $contrat->id }}" {{ old('contrat_id') == $contrat->id ? 'selected' : '' }}>
                                    {{ $contrat->numero_contrat }} — {{ $contrat->assurance->nom ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('contrat_id')
                            <div class="text-danger mt-1" style="font-size:.8rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                                Date du sinistre <span style="color:var(--orange);">*</span>
                            </label>
                            <input type="date" name="date_sinistre"
                                   class="form-control @error('date_sinistre') is-invalid @enderror"
                                   max="{{ date('Y-m-d') }}" value="{{ old('date_sinistre') }}" required>
                            @error('date_sinistre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                                Lieu du sinistre
                            </label>
                            <input type="text" name="lieu"
                                   class="form-control" placeholder="Ex: Dakar, Plateau"
                                   value="{{ old('lieu') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Description détaillée <span style="color:var(--orange);">*</span>
                        </label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="4" placeholder="Décrivez les circonstances du sinistre (min. 20 caractères)..."
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Montant réclamé (XOF)
                        </label>
                        <div class="input-group">
                            <input type="number" name="montant_reclame"
                                   class="form-control" placeholder="0"
                                   value="{{ old('montant_reclame') }}" min="0">
                            <span class="input-group-text" style="background:#F7FAFC; border-color:#E2E8F0;">XOF</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Documents justificatifs
                        </label>
                        <div style="border:2px dashed #E2E8F0; border-radius:12px; padding:2rem; text-align:center; cursor:pointer; transition:all .2s;"
                             id="dropzone"
                             onclick="document.getElementById('fileInput').click()"
                             ondragover="event.preventDefault(); this.style.borderColor='var(--orange)'"
                             ondragleave="this.style.borderColor='#E2E8F0'">
                            <i class="bi bi-cloud-upload" style="font-size:2rem; color:#CBD5E0; display:block; margin-bottom:.5rem;"></i>
                            <p style="color:var(--text-muted); font-size:.875rem; margin:0;">
                                Glissez vos fichiers ici ou <span style="color:var(--orange); font-weight:600;">parcourir</span>
                            </p>
                            <p style="color:#CBD5E0; font-size:.75rem; margin-top:.25rem;">JPG, PNG, PDF — Max. 5MB</p>
                        </div>
                        <input type="file" name="documents[]" id="fileInput" multiple
                               accept=".jpg,.jpeg,.png,.pdf" style="display:none;"
                               onchange="showFiles(this)">
                        <div id="fileList" class="mt-2"></div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('client.sinistres.index') }}"
                           class="btn btn-outline-secondary" style="border-radius:10px;">
                            Annuler
                        </a>
                        <button type="submit" class="btn-orange btn">
                            <i class="bi bi-send me-1"></i> Soumettre la déclaration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info panel --}}
    <div class="col-md-4">
        <div class="card-sunu card mb-3" style="background:var(--dark);">
            <div class="card-body">
                <h6 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; color:white; margin-bottom:1rem;">
                    <i class="bi bi-info-circle me-2" style="color:var(--orange);"></i>
                    Comment ça marche ?
                </h6>
                @foreach([
                    ['bi-1-circle', 'Déclaration', 'Remplissez ce formulaire avec tous les détails.'],
                    ['bi-2-circle', 'Instruction', 'Un agent examine votre dossier sous 48h.'],
                    ['bi-3-circle', 'Décision', 'Vous recevez une décision et le montant accordé.'],
                    ['bi-4-circle', 'Indemnisation', 'Le virement est effectué sur votre compte mobile.'],
                ] as [$icon, $title, $desc])
                <div class="d-flex gap-2 mb-3">
                    <i class="bi {{ $icon }}" style="color:var(--orange); font-size:1.1rem; flex-shrink:0; margin-top:.1rem;"></i>
                    <div>
                        <div style="font-size:.82rem; font-weight:700; color:white;">{{ $title }}</div>
                        <div style="font-size:.78rem; color:rgba(255,255,255,.5);">{{ $desc }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card-sunu card" style="border-left:3px solid var(--orange);">
            <div class="card-body" style="padding:1rem;">
                <div style="font-size:.8rem; font-weight:700; color:var(--text-dark); margin-bottom:.4rem;">
                    📞 Urgence sinistre
                </div>
                <div style="font-size:1.1rem; font-weight:800; color:var(--orange); font-family:'Plus Jakarta Sans',sans-serif;">
                    33 800 00 00
                </div>
                <div style="font-size:.75rem; color:var(--text-muted);">Disponible 24h/24, 7j/7</div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showFiles(input) {
        const list = document.getElementById('fileList');
        list.innerHTML = '';
        Array.from(input.files).forEach(f => {
            list.innerHTML += `
                <div style="background:#F8F9FA; border-radius:8px; padding:.4rem .75rem; margin-top:.3rem; font-size:.8rem; display:flex; align-items:center; gap:.5rem;">
                    <i class="bi bi-file-earmark" style="color:var(--orange);"></i>
                    ${f.name} <span style="color:var(--text-muted);">(${(f.size/1024).toFixed(0)} Ko)</span>
                </div>`;
        });
    }
</script>
@endpush
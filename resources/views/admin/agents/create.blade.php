@extends('layouts.dashboard')
@section('title', 'Créer un Agent')
@section('page-title', 'Nouvel Agent')
@section('page-subtitle', 'Ajouter un membre à l\'équipe')

@section('content')

<div class="mb-3">
    <a href="{{ route('admin.agents.index') }}"
       style="color:var(--text-muted); font-size:.875rem; text-decoration:none; font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row g-3 justify-content-center">
    <div class="col-md-7">
        <div class="card-sunu card">
            <div class="card-header"><i class="bi bi-person-badge me-2 text-warning"></i>Informations de l'agent</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.agents.store') }}">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Prénom</label>
                            <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                                   value="{{ old('prenom') }}" required>
                            @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Nom</label>
                            <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom') }}" required>
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Téléphone</label>
                            <input type="text" name="telephone" class="form-control"
                                   value="{{ old('telephone') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Matricule</label>
                            <input type="text" name="matricule" class="form-control"
                                   value="{{ old('matricule') }}" placeholder="AGT-001">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Zone couverte</label>
                        <input type="text" name="zone_couverte" class="form-control"
                               value="{{ old('zone_couverte') }}" placeholder="Ex: Dakar, Thiès...">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Mot de passe</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 caractères" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Confirmation</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('admin.agents.index') }}"
                           class="btn btn-outline-secondary" style="border-radius:10px;">Annuler</a>
                        <button type="submit" class="btn-orange btn">
                            <i class="bi bi-plus me-1"></i> Créer l'agent
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
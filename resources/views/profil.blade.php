@extends('layouts.dashboard')
@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')
@section('page-subtitle', 'Gérez vos informations personnelles')

@section('content')

<div class="row g-3">

    {{-- Infos personnelles --}}
    <div class="col-md-8">
        <div class="card-sunu card mb-3">
            <div class="card-header"><i class="bi bi-person me-2 text-warning"></i>Informations personnelles</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Prénom</label>
                            <input type="text" name="prenom" class="form-control"
                                   value="{{ old('prenom', $user->prenom) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Nom</label>
                            <input type="text" name="nom" class="form-control"
                                   value="{{ old('nom', $user->nom) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Email</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled
                               style="background:#F8F9FA; color:var(--text-muted);">
                        <small style="color:var(--text-muted); font-size:.75rem;">L'email ne peut pas être modifié.</small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Téléphone</label>
                            <input type="text" name="telephone" class="form-control"
                                   value="{{ old('telephone', $user->telephone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Adresse</label>
                            <input type="text" name="adresse" class="form-control"
                                   value="{{ old('adresse', $user->adresse) }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Photo de profil</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" class="btn-orange btn">
                        <i class="bi bi-check2 me-1"></i> Enregistrer les modifications
                    </button>
                </form>
            </div>
        </div>

        {{-- Changer mot de passe --}}
        <div class="card-sunu card">
            <div class="card-header"><i class="bi bi-lock me-2 text-warning"></i>Changer le mot de passe</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profil.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Mot de passe actuel</label>
                        <input type="password" name="ancien_password"
                               class="form-control @error('ancien_password') is-invalid @enderror"
                               placeholder="••••••••">
                        @error('ancien_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Nouveau mot de passe</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 caractères">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">Confirmation</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control" placeholder="Répétez">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-outline-secondary" style="border-radius:10px; font-weight:600;">
                        <i class="bi bi-lock me-1"></i> Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Carte profil résumé --}}
    <div class="col-md-4">
        <div class="card-sunu card" style="text-align:center;">
            <div class="card-body py-4">
                <div style="width:80px; height:80px; border-radius:20px; background:var(--orange); display:flex; align-items:center; justify-content:center; font-size:1.8rem; font-weight:800; color:white; margin:0 auto 1rem; font-family:'Plus Jakarta Sans',sans-serif;">
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" style="width:100%; height:100%; object-fit:cover; border-radius:20px;">
                    @else
                        {{ strtoupper(substr($user->prenom,0,1)) }}{{ strtoupper(substr($user->nom,0,1)) }}
                    @endif
                </div>

                <h5 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:.25rem;">
                    {{ $user->nom_complet }}
                </h5>
                <span style="background:var(--orange-light); color:var(--orange); font-size:.75rem; font-weight:700; padding:.25rem .75rem; border-radius:20px; text-transform:uppercase;">
                    {{ $user->type }}
                </span>

                <hr style="margin:1.25rem 0; border-color:var(--gray-border);">

                <div class="d-flex flex-column gap-2">
                    @foreach([
                        ['bi-envelope', $user->email],
                        ['bi-phone', $user->telephone ?? 'Non renseigné'],
                        ['bi-geo-alt', $user->adresse ?? 'Non renseignée'],
                    ] as [$icon, $val])
                    <div class="d-flex align-items-center gap-2" style="font-size:.82rem; color:var(--text-muted);">
                        <i class="bi {{ $icon }}" style="width:16px;"></i>
                        <span>{{ $val }}</span>
                    </div>
                    @endforeach
                </div>

                @if($user->isClient() && $user->date_naissance)
                <hr style="margin:1rem 0; border-color:var(--gray-border);">
                <div style="font-size:.8rem; color:var(--text-muted);">
                    Membre depuis le {{ $user->created_at?->format('d/m/Y') }}
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
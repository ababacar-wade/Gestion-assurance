@extends('layouts.auth')
@section('title', 'Inscription')

@section('content')
    <h3>Créer votre compte</h3>
    <p class="subtitle">Rejoignez SunuKarangué et protégez ce qui compte</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="row g-3 mb-3">
            <div class="col-6">
                <label class="form-label">Prénom</label>
                <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                       value="{{ old('prenom') }}" placeholder="Moussa" required>
                @error('prenom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6">
                <label class="form-label">Nom</label>
                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                       value="{{ old('nom') }}" placeholder="Ndiaye" required>
                @error('nom')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Adresse email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="vous@exemple.com" required>
            </div>
            @error('email')
                <div class="text-danger mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Téléphone</label>
            <div class="input-group">
                <span class="input-group-text">🇸🇳 +221</span>
                <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror"
                       value="{{ old('telephone') }}" placeholder="77 000 00 00" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Date de naissance</label>
            <input type="date" name="date_naissance"
                   class="form-control @error('date_naissance') is-invalid @enderror"
                   value="{{ old('date_naissance') }}" required>
            @error('date_naissance')
                <div class="text-danger mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Min. 8 caractères" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6">
                <label class="form-label">Confirmation</label>
                <input type="password" name="password_confirmation"
                       class="form-control" placeholder="Répétez" required>
            </div>
        </div>

        <div class="mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="cgu" required>
                <label class="form-check-label" for="cgu" style="font-size:.825rem;">
                    J'accepte les <a href="#" class="auth-link">conditions d'utilisation</a>
                    et la <a href="#" class="auth-link">politique de confidentialité</a>
                </label>
            </div>
        </div>

        <button type="submit" class="btn-auth btn">
            Créer mon compte <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </form>

    <p class="text-center mt-4" style="font-size:.875rem; color:var(--text-muted);">
        Déjà un compte ?
        <a href="{{ route('login') }}" class="auth-link">Se connecter</a>
    </p>
@endsection
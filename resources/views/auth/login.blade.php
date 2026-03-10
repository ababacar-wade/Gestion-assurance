@extends('layouts.auth')
@section('title', 'Connexion')

@section('content')
    <h3>Bon retour 👋</h3>
    <p class="subtitle">Connectez-vous à votre espace personnel</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Adresse email</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="vous@exemple.com" required autofocus>
            </div>
            @error('email')
                <div class="text-danger mt-1" style="font-size:.8rem;">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label">Mot de passe</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePwd"
                        style="border-radius:0 10px 10px 0; border-color:#E2E8F0;">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="font-size:.875rem;">
                    Se souvenir de moi
                </label>
            </div>
            <a href="#" class="auth-link" style="font-size:.875rem;">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn-auth btn">
            Se connecter <i class="bi bi-arrow-right ms-1"></i>
        </button>
    </form>

    <p class="text-center mt-4" style="font-size:.875rem; color:var(--text-muted);">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="auth-link">Créer un compte</a>
    </p>

    {{-- Accès rapide démo --}}
    <div class="mt-4 p-3 rounded-3" style="background:#F7FAFC; border:1px dashed #E2E8F0;">
        <p class="mb-2" style="font-size:.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">
            Accès démo rapide
        </p>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-sm" style="font-size:.75rem; background:#EDF2F7;"
                    onclick="fillDemo('client@test.sn')">
                👤 Client
            </button>
            <button type="button" class="btn btn-sm" style="font-size:.75rem; background:#EDF2F7;"
                    onclick="fillDemo('agent@assurance.sn')">
                🧑‍💼 Agent
            </button>
            <button type="button" class="btn btn-sm" style="font-size:.75rem; background:#EDF2F7;"
                    onclick="fillDemo('admin@assurance.sn')">
                🔴 Admin
            </button>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function fillDemo(email) {
        document.querySelector('input[name="email"]').value = email;
        document.querySelector('input[name="password"]').value = 'password';
    }

    document.getElementById('togglePwd').addEventListener('click', function() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            pwd.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
</script>
@endpush
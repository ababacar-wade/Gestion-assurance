<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Authentification') - SunuKarangué</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --orange:       #FF6B2B;
            --orange-dark:  #E55A1F;
            --orange-light: #FFF0EA;
            --dark:         #1A1A2E;
            --text-dark:    #2D3748;
            --text-muted:   #718096;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
        }

        /* ── Panel gauche orange ──────────────────────────────── */
        .auth-left {
            width: 45%;
            background: var(--orange);
            background-image:
                radial-gradient(circle at 20% 80%, rgba(255,255,255,.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(0,0,0,.1) 0%, transparent 50%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,.08);
        }

        .auth-left::after {
            content: '';
            position: absolute;
            top: -40px;
            left: -40px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(0,0,0,.08);
        }

        .auth-brand {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--white);
            margin-bottom: 3rem;
            position: relative;
            z-index: 2;
        }

        .auth-brand span { color: rgba(255,255,255,.7); }

        .auth-left h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 2.2rem;
            color: var(--white);
            line-height: 1.3;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
        }

        .auth-left h2 em {
            font-style: normal;
            color: rgba(255,255,255,.75);
        }

        .auth-left p {
            color: rgba(255,255,255,.8);
            font-size: .95rem;
            line-height: 1.6;
            position: relative;
            z-index: 2;
        }

        .auth-features {
            margin-top: 2.5rem;
            position: relative;
            z-index: 2;
        }

        .auth-feature-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: rgba(255,255,255,.9);
            font-size: .875rem;
            font-weight: 500;
            margin-bottom: .75rem;
        }

        .auth-feature-icon {
            width: 32px;
            height: 32px;
            background: rgba(255,255,255,.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        /* ── Panel droit formulaire ───────────────────────────── */
        .auth-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            background: #fff;
            overflow-y: auto;
        }

        .auth-form-wrapper {
            width: 100%;
            max-width: 420px;
        }

        .auth-form-wrapper h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--text-dark);
            margin-bottom: .4rem;
        }

        .auth-form-wrapper p.subtitle {
            color: var(--text-muted);
            font-size: .9rem;
            margin-bottom: 2rem;
        }

        /* ── Inputs ───────────────────────────────────────────── */
        .form-label {
            font-weight: 600;
            font-size: .82rem;
            color: var(--text-dark);
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .form-control {
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            padding: .7rem 1rem;
            font-size: .9rem;
            transition: all .2s;
            color: var(--text-dark);
        }

        .form-control:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255,107,43,.12);
        }

        .input-group-text {
            background: #F7FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px 0 0 10px;
            color: var(--text-muted);
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        /* ── Bouton submit ────────────────────────────────────── */
        .btn-auth {
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: .8rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            transition: all .2s;
        }

        .btn-auth:hover {
            background: var(--orange-dark);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(255,107,43,.35);
        }

        /* ── Lien bas de page ─────────────────────────────────── */
        .auth-link {
            color: var(--orange);
            font-weight: 600;
            text-decoration: none;
        }

        .auth-link:hover { text-decoration: underline; }

        /* ── Responsive ───────────────────────────────────────── */
        @media (max-width: 768px) {
            .auth-left { display: none; }
            .auth-right { padding: 2rem 1.5rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

<div class="auth-left d-none d-md-flex">
    <div class="auth-brand">Sunu<span>Karangué</span></div>

    <h2>Protégez ce qui compte <em>vraiment.</em></h2>
    <p>La plateforme d'assurance moderne au Sénégal. Simple, rapide, fiable.</p>

    <div class="auth-features">
        <div class="auth-feature-item">
            <div class="auth-feature-icon"><i class="bi bi-shield-check"></i></div>
            Couverture complète Auto, Habitat & Vie
        </div>
        <div class="auth-feature-item">
            <div class="auth-feature-icon"><i class="bi bi-phone"></i></div>
            Paiement Wave & Orange Money
        </div>
        <div class="auth-feature-item">
            <div class="auth-feature-icon"><i class="bi bi-clock-history"></i></div>
            Déclaration de sinistre en ligne 24/7
        </div>
        <div class="auth-feature-item">
            <div class="auth-feature-icon"><i class="bi bi-people"></i></div>
            +4 800 clients satisfaits
        </div>
    </div>
</div>

<div class="auth-right">
    <div class="auth-form-wrapper">

        {{-- Alerts --}}
        @if($errors->any())
            <div class="alert alert-danger rounded-3 mb-3" style="font-size:.875rem;">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success rounded-3 mb-3">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
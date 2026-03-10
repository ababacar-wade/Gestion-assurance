<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sunu Karangué') - Assurance</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ── Variables de couleurs ─────────────────────────────── */
        :root {
            --orange:        #FF6B2B;
            --orange-dark:   #E55A1F;
            --orange-light:  #FFF0EA;
            --dark:          #1A1A2E;
            --dark-2:        #16213E;
            --gray-bg:       #F8F9FA;
            --gray-border:   #E9ECEF;
            --text-dark:     #2D3748;
            --text-muted:    #718096;
            --white:         #FFFFFF;
            --success:       #38A169;
            --danger:        #E53E3E;
            --warning:       #D69E2E;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background: var(--white);
        }

        h1, h2, h3, h4, h5, h6, .fw-bold, .display-1, .display-2, .display-3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── Navbar ───────────────────────────────────────────── */
        .navbar-sunu {
            background: var(--white);
            border-bottom: 1px solid var(--gray-border);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,.06);
        }

        .navbar-brand-sunu {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--dark) !important;
            text-decoration: none;
        }

        .navbar-brand-sunu span {
            color: var(--orange);
        }

        .nav-link-sunu {
            color: var(--text-muted) !important;
            font-weight: 500;
            font-size: .9rem;
            padding: .5rem 1rem !important;
            border-radius: 8px;
            transition: all .2s;
        }

        .nav-link-sunu:hover, .nav-link-sunu.active {
            color: var(--orange) !important;
            background: var(--orange-light);
        }

        /* ── Boutons ──────────────────────────────────────────── */
        .btn-orange {
            background: var(--orange);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            padding: .6rem 1.5rem;
            transition: all .2s;
        }

        .btn-orange:hover {
            background: var(--orange-dark);
            color: var(--white);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(255,107,43,.35);
        }

        .btn-outline-orange {
            background: transparent;
            color: var(--orange);
            border: 2px solid var(--orange);
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            padding: .5rem 1.5rem;
            transition: all .2s;
        }

        .btn-outline-orange:hover {
            background: var(--orange);
            color: var(--white);
        }

        /* ── Footer ───────────────────────────────────────────── */
        .footer-sunu {
            background: var(--dark);
            color: rgba(255,255,255,.7);
            padding: 3rem 0 1.5rem;
        }

        .footer-sunu h6 {
            color: var(--white);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-sunu a {
            color: rgba(255,255,255,.6);
            text-decoration: none;
            font-size: .875rem;
            display: block;
            margin-bottom: .4rem;
            transition: color .2s;
        }

        .footer-sunu a:hover { color: var(--orange); }

        .footer-brand {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 1.3rem;
            color: var(--white);
        }

        .footer-brand span { color: var(--orange); }

        /* ── Alerts flash ─────────────────────────────────────── */
        .alert-flash {
            border-radius: 12px;
            border: none;
            font-weight: 500;
        }

        @yield('extra-css')
    </style>

    @stack('styles')
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar-sunu">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">

                <a href="{{ route('home') }}" class="navbar-brand-sunu">
                    Sunu<span>Karangué</span>
                </a>

                <div class="d-none d-md-flex align-items-center gap-1">
                    <a href="{{ route('home') }}"
                       class="nav-link-sunu {{ request()->routeIs('home') ? 'active' : '' }}">
                        Accueil
                    </a>
                    <a href="{{ route('assurances.index') }}"
                       class="nav-link-sunu {{ request()->routeIs('assurances.*') ? 'active' : '' }}">
                        Nos Offres
                    </a>
                    <a href="#contact" class="nav-link-sunu">Contact</a>
                </div>

                <div class="d-flex align-items-center gap-2">
                    @guest
                        <a href="{{ route('login') }}" class="btn-outline-orange btn btn-sm">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="btn-orange btn btn-sm">
                            S'inscrire
                        </a>
                    @else
                        <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isAgent() ? route('agent.dashboard') : route('client.dashboard')) }}"
                           class="btn-orange btn btn-sm">
                            <i class="bi bi-grid me-1"></i> Mon Espace
                        </a>
                    @endguest
                </div>

            </div>
        </div>
    </nav>

    {{-- Alerts flash --}}
    @if(session('success') || session('error') || session('info') || session('warning'))
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-flash alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-flash alert-dismissible fade show">
                <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-flash alert-dismissible fade show">
                <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
    @endif

    {{-- Contenu principal --}}
    @yield('content')

    {{-- Footer --}}
    <footer class="footer-sunu">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="footer-brand mb-2">Sunu<span>Karangué</span></div>
                    <p style="font-size:.875rem; color:rgba(255,255,255,.6);">
                        Votre partenaire de confiance pour toutes vos assurances au Sénégal.
                    </p>
                </div>
                <div class="col-md-2">
                    <h6>Produits</h6>
                    <a href="#">Assurance Auto</a>
                    <a href="#">Assurance Habitat</a>
                    <a href="#">Assurance Vie</a>
                </div>
                <div class="col-md-2">
                    <h6>Société</h6>
                    <a href="#">À propos</a>
                    <a href="#">Nos agences</a>
                    <a href="#">Carrières</a>
                </div>
                <div class="col-md-2">
                    <h6>Support</h6>
                    <a href="#">FAQ</a>
                    <a href="#">Contact</a>
                    <a href="#">Sinistres</a>
                </div>
                <div class="col-md-2">
                    <h6>Légal</h6>
                    <a href="#">CGU</a>
                    <a href="#">Confidentialité</a>
                </div>
            </div>
            <hr style="border-color:rgba(255,255,255,.1);">
            <p class="text-center mb-0" style="font-size:.8rem; color:rgba(255,255,255,.4);">
                © {{ date('Y') }} SunuKarangué. Tous droits réservés.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
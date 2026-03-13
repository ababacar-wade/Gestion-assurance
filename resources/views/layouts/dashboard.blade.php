<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SunuKarangué</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --orange:        #FF6B2B;
            --orange-dark:   #E55A1F;
            --orange-light:  #FFF0EA;
            --sidebar-bg:    #1A1A2E;
            --sidebar-hover: #16213E;
            --sidebar-active:#FF6B2B;
            --gray-bg:       #F4F6F9;
            --gray-border:   #E9ECEF;
            --text-dark:     #2D3748;
            --text-muted:    #718096;
            --white:         #FFFFFF;
            --card-shadow:   0 2px 12px rgba(0,0,0,.06);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-bg);
            color: var(--text-dark);
        }

        h1,h2,h3,h4,h5,h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── Layout principal ─────────────────────────────────── */
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ──────────────────────────────────────────── */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
            transition: all .3s;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        .sidebar-brand a {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 1.3rem;
            color: var(--white);
            text-decoration: none;
        }

        .sidebar-brand a span { color: var(--orange); }

        .sidebar-role-badge {
            display: inline-block;
            background: rgba(255,107,43,.15);
            color: var(--orange);
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            padding: .2rem .6rem;
            border-radius: 20px;
            margin-top: .3rem;
        }

        /* ── Nav items ────────────────────────────────────────── */
        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }

        .sidebar-section-title {
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255,255,255,.3);
            padding: .75rem 1.5rem .3rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem 1.5rem;
            color: rgba(255,255,255,.65);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            transition: all .2s;
            border-left: 3px solid transparent;
            margin: .1rem 0;
        }

        .sidebar-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-link:hover {
            color: var(--white);
            background: rgba(255,255,255,.06);
        }

        .sidebar-link.active {
            color: var(--white);
            background: rgba(255,107,43,.15);
            border-left-color: var(--orange);
        }

        .sidebar-link.active i { color: var(--orange); }

        /* Badge compteur dans sidebar */
        .sidebar-badge {
            margin-left: auto;
            background: var(--orange);
            color: white;
            font-size: .65rem;
            font-weight: 700;
            padding: .15rem .5rem;
            border-radius: 20px;
            min-width: 20px;
            text-align: center;
        }

        /* ── Sidebar bottom (user) ────────────────────────────── */
        .sidebar-user {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255,255,255,.06);
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .sidebar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: .875rem;
            flex-shrink: 0;
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: .82rem;
            font-weight: 600;
            color: var(--white);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: .72rem;
            color: rgba(255,255,255,.4);
        }

        .sidebar-logout {
            /* Reset total des styles Bootstrap sur <button> */
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            cursor: pointer;
            outline: none;
            /* Style custom */
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            color: rgba(255,255,255,.45);
            font-size: 1.15rem;
            transition: all .2s;
        }

        .sidebar-logout:hover {
            color: #FC8181;
            background: rgba(229,62,62,.18);
        }

        /* ── Contenu principal ────────────────────────────────── */
        .main-content {
            margin-left: 250px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Topbar ───────────────────────────────────────────── */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--gray-border);
            padding: .875rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
            box-shadow: 0 2px 8px rgba(0,0,0,.04);
        }

        .topbar-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--text-dark);
        }

        .topbar-subtitle {
            font-size: .78rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .topbar-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--gray-bg);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 1rem;
            cursor: pointer;
            transition: all .2s;
            position: relative;
            text-decoration: none;
        }

        .topbar-icon-btn:hover {
            background: var(--orange-light);
            color: var(--orange);
        }

        .notif-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: var(--orange);
            border-radius: 50%;
            border: 2px solid white;
        }

        .topbar-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--orange);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: .875rem;
            cursor: pointer;
            text-decoration: none;
        }

        /* ── Page content ─────────────────────────────────────── */
        .page-content {
            padding: 1.5rem;
            flex: 1;
        }

        /* ── Cards ────────────────────────────────────────────── */
        .card-sunu {
            background: var(--white);
            border: none;
            border-radius: 16px;
            box-shadow: var(--card-shadow);
        }

        .card-sunu .card-header {
            background: transparent;
            border-bottom: 1px solid var(--gray-border);
            padding: 1.25rem 1.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: .95rem;
        }

        .card-sunu .card-body { padding: 1.5rem; }

        /* ── Stats cards ──────────────────────────────────────── */
        .stat-card {
            background: var(--white);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            border: none;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .stat-icon.orange { background: var(--orange-light); color: var(--orange); }
        .stat-icon.green  { background: #F0FFF4; color: #38A169; }
        .stat-icon.blue   { background: #EBF8FF; color: #3182CE; }
        .stat-icon.red    { background: #FFF5F5; color: #E53E3E; }

        .stat-value {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--text-dark);
            line-height: 1;
        }

        .stat-label {
            font-size: .8rem;
            color: var(--text-muted);
            margin-top: .2rem;
        }

        /* ── Tableaux ─────────────────────────────────────────── */
        .table-sunu {
            font-size: .875rem;
        }

        .table-sunu thead th {
            background: var(--gray-bg);
            color: var(--text-muted);
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            border: none;
            padding: .75rem 1rem;
        }

        .table-sunu tbody td {
            padding: .875rem 1rem;
            border-color: var(--gray-border);
            vertical-align: middle;
        }

        .table-sunu tbody tr:hover { background: #FAFAFA; }

        /* ── Badges statuts ───────────────────────────────────── */
        .badge-statut {
            padding: .35rem .75rem;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
        }

        .badge-actif     { background: #F0FFF4; color: #38A169; }
        .badge-attente   { background: #FFFBEB; color: #D69E2E; }
        .badge-suspendu  { background: #EDF2F7; color: #718096; }
        .badge-resilié   { background: #FFF5F5; color: #E53E3E; }
        .badge-expire    { background: #EDF2F7; color: #4A5568; }
        .badge-declare   { background: #EBF8FF; color: #3182CE; }
        .badge-instruct  { background: #FAF5FF; color: #805AD5; }
        .badge-accepte   { background: #F0FFF4; color: #38A169; }
        .badge-refuse    { background: #FFF5F5; color: #E53E3E; }
        .badge-indemnise { background: #F0FFF4; color: #2F855A; }

        /* ── Boutons ──────────────────────────────────────────── */
        .btn-orange {
            background: var(--orange);
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            padding: .55rem 1.25rem;
            font-size: .875rem;
            transition: all .2s;
        }

        .btn-orange:hover {
            background: var(--orange-dark);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255,107,43,.3);
        }

        .btn-outline-orange {
            background: transparent;
            color: var(--orange);
            border: 1.5px solid var(--orange);
            border-radius: 10px;
            font-weight: 600;
            padding: .5rem 1.25rem;
            font-size: .875rem;
            transition: all .2s;
        }

        .btn-outline-orange:hover {
            background: var(--orange);
            color: white;
        }

        /* ── Mobile sidebar toggle ────────────────────────────── */
        .sidebar-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.3rem;
            color: var(--text-dark);
            padding: .25rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar-toggle {
                display: block;
            }
        }

        /* ── Alerts ───────────────────────────────────────────── */
        .alert { border-radius: 12px; border: none; font-size: .875rem; }
    </style>
    @stack('styles')
</head>
<body>

<div class="dashboard-wrapper">

    {{-- ── SIDEBAR ─────────────────────────────────────────── --}}
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <a href="{{ route('home') }}">Sunu<span>Karangué</span></a>
            <div>
                <span class="sidebar-role-badge">
                    {{ Auth::user()->isAdmin() ? 'Administrateur' : (Auth::user()->isAgent() ? 'Agent' : 'Client') }}
                </span>
            </div>
        </div>

        <nav class="sidebar-nav">

            {{-- ── CLIENT ── --}}
            @if(Auth::user()->isClient())
                <div class="sidebar-section-title">Principal</div>

                <a href="{{ route('client.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Tableau de bord
                </a>

                <div class="sidebar-section-title">Mes Assurances</div>

                <a href="{{ route('client.contrats.index') }}"
                   class="sidebar-link {{ request()->routeIs('client.contrats.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> Mes Contrats
                </a>

                <a href="{{ route('client.contrats.create') }}"
                   class="sidebar-link {{ request()->routeIs('client.contrats.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i> Souscrire
                </a>

                <div class="sidebar-section-title">Finances</div>

                <a href="{{ route('client.payments.index') }}"
                   class="sidebar-link {{ request()->routeIs('client.payments.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i> Paiements
                </a>

                <div class="sidebar-section-title">Sinistres</div>

                <a href="{{ route('client.sinistres.index') }}"
                   class="sidebar-link {{ request()->routeIs('client.sinistres.*') ? 'active' : '' }}">
                    <i class="bi bi-exclamation-triangle"></i> Mes Sinistres
                </a>

                <a href="{{ route('client.sinistres.create') }}"
                   class="sidebar-link">
                    <i class="bi bi-plus-circle"></i> Déclarer
                </a>
            @endif

            {{-- ── AGENT ── --}}
            @if(Auth::user()->isAgent())
                <div class="sidebar-section-title">Principal</div>

                <a href="{{ route('agent.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Tableau de bord
                </a>

                <div class="sidebar-section-title">Gestion</div>

                <a href="{{ route('agent.contrats.index') }}"
                   class="sidebar-link {{ request()->routeIs('agent.contrats.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> Contrats
                </a>

                <a href="{{ route('agent.sinistres.index') }}"
                   class="sidebar-link {{ request()->routeIs('agent.sinistres.*') ? 'active' : '' }}">
                    <i class="bi bi-exclamation-triangle"></i> Sinistres
                </a>

                <a href="{{ route('agent.clients.index') }}"
                   class="sidebar-link {{ request()->routeIs('agent.clients.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Clients
                </a>
            @endif

            {{-- ── ADMIN ── --}}
            @if(Auth::user()->isAdmin())
                <div class="sidebar-section-title">Principal</div>

                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Tableau de bord
                </a>

                <div class="sidebar-section-title">Utilisateurs</div>

                <a href="{{ route('admin.clients.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Clients
                </a>

                <a href="{{ route('admin.agents.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.agents.*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i> Agents
                </a>

                <div class="sidebar-section-title">Catalogue</div>

                <a href="{{ route('admin.assurances.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.assurances.*') ? 'active' : '' }}">
                    <i class="bi bi-shield"></i> Assurances
                </a>

                <div class="sidebar-section-title">Gestion</div>

                <a href="{{ route('admin.contrats.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.contrats.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> Contrats
                </a>

                <a href="{{ route('admin.payments.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card"></i> Paiements
                </a>

                <a href="{{ route('admin.sinistres.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.sinistres.*') ? 'active' : '' }}">
                    <i class="bi bi-exclamation-triangle"></i> Sinistres
                </a>
            @endif

            {{-- Profil (tous) --}}
            <div class="sidebar-section-title">Compte</div>
            <a href="{{ route('profil') }}"
               class="sidebar-link {{ request()->routeIs('profil*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Mon Profil
            </a>

        </nav>

        {{-- User en bas de sidebar --}}
        <div class="sidebar-user">
            {{-- Avatar avec initiales ou photo --}}
            @if(Auth::user()->photo)
                <img src="{{ asset('storage/' . Auth::user()->photo) }}"
                     style="width:36px;height:36px;border-radius:10px;object-fit:cover;flex-shrink:0;">
            @else
                <div class="sidebar-avatar">
                    {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                </div>
            @endif

            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</div>
                <div class="sidebar-user-role">{{ Auth::user()->type }}</div>
            </div>

            {{-- Bouton déconnexion --}}
            <form method="POST" action="{{ route('logout') }}" style="margin:0;padding:0;">
                @csrf
                <button type="submit" class="sidebar-logout" title="Se déconnecter">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>

    </aside>

    {{-- ── MAIN ──────────────────────────────────────────────── --}}
    <div class="main-content">

        {{-- Topbar --}}
        <div class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <div class="topbar-title">@yield('page-title', 'Tableau de bord')</div>
                    <div class="topbar-subtitle">@yield('page-subtitle', '')</div>
                </div>
            </div>

            <div class="topbar-right">
                <a href="#" class="topbar-icon-btn">
                    <i class="bi bi-bell"></i>
                    <span class="notif-dot"></span>
                </a>
                <a href="{{ route('profil') }}" class="topbar-avatar">
                    {{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                </a>
            </div>
        </div>

        {{-- Alerts flash --}}
        @if(session('success') || session('error') || session('info'))
        <div class="px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
        @endif

        {{-- Contenu de la page --}}
        <div class="page-content">
            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle sidebar mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('open');
    });
</script>
@stack('scripts')
</body>
</html>
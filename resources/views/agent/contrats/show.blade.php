@extends('layouts.dashboard')
@section('title', 'Contrat ' . $contrat->numero_contrat)
@section('page-title', 'Détail du Contrat')
@section('page-subtitle', $contrat->numero_contrat)

@section('content')

<div class="mb-3">
    <a href="{{ route('agent.contrats.index') }}"
       style="color:var(--text-muted); font-size:.875rem; text-decoration:none; font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Retour aux contrats
    </a>
</div>

<div class="row g-3">
    <div class="col-md-8">

        {{-- En-tête contrat --}}
        <div class="card-sunu card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:52px; height:52px; background:var(--orange-light); border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem;">
                            {{ $contrat->assurance?->type === 'auto' ? '🚗' : ($contrat->assurance?->type === 'habitat' ? '🏠' : '❤️') }}
                        </div>
                        <div>
                            <h5 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin:0;">
                                {{ $contrat->assurance->nom ?? '—' }}
                            </h5>
                            <span style="font-size:.8rem; color:var(--text-muted);">{{ $contrat->numero_contrat }}</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @php $map = ['actif'=>'badge-actif','en_attente'=>'badge-attente','suspendu'=>'badge-suspendu','resilié'=>'badge-resilié','expire'=>'badge-expire']; @endphp
                        <span class="badge-statut {{ $map[$contrat->statut] ?? '' }}">
                            {{ ucfirst(str_replace('_',' ',$contrat->statut)) }}
                        </span>
                        <a href="{{ route('agent.contrats.edit', $contrat) }}"
                           class="btn-orange btn btn-sm">
                            <i class="bi bi-pencil me-1"></i> Modifier
                        </a>
                    </div>
                </div>

                <hr style="border-color:var(--gray-border); margin:1.25rem 0;">

                <div class="row g-3">
                    @foreach([
                        ['Date début',    $contrat->date_debut?->format('d/m/Y')],
                        ['Date fin',      $contrat->date_fin?->format('d/m/Y')],
                        ['Périodicité',   ucfirst($contrat->periodicite)],
                        ['Franchise',     number_format($contrat->franchise, 0, ',', ' ') . ' XOF'],
                    ] as [$label, $val])
                    <div class="col-6 col-md-3">
                        <div style="font-size:.75rem; color:var(--text-muted); margin-bottom:.2rem;">{{ $label }}</div>
                        <div style="font-weight:600; font-size:.875rem;">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Infos client --}}
        <div class="card-sunu card mb-3">
            <div class="card-header"><i class="bi bi-person me-2 text-warning"></i>Informations client</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:48px; height:48px; background:var(--orange-light); border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:700; color:var(--orange); font-size:1rem; font-family:'Plus Jakarta Sans',sans-serif;">
                        {{ strtoupper(substr($contrat->client->prenom ?? 'C', 0, 1)) }}{{ strtoupper(substr($contrat->client->nom ?? 'L', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700; font-family:'Plus Jakarta Sans',sans-serif;">
                            {{ $contrat->client->nom_complet ?? '—' }}
                        </div>
                        <div style="font-size:.8rem; color:var(--text-muted);">Client</div>
                    </div>
                    <a href="{{ route('agent.clients.show', $contrat->client) }}"
                       class="btn btn-sm ms-auto" style="background:#F4F6F9; border-radius:8px; font-size:.78rem; font-weight:600;">
                        Voir profil →
                    </a>
                </div>
                <div class="row g-2">
                    @foreach([
                        ['bi-envelope', $contrat->client->email ?? '—'],
                        ['bi-phone',    $contrat->client->telephone ?? '—'],
                        ['bi-geo-alt',  $contrat->client->adresse ?? 'Non renseignée'],
                    ] as [$icon, $val])
                    <div class="col-md-4">
                        <div style="font-size:.82rem; color:var(--text-muted); display:flex; align-items:center; gap:.4rem;">
                            <i class="bi {{ $icon }}" style="color:var(--orange);"></i> {{ $val }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Historique paiements --}}
        <div class="card-sunu card mb-3">
            <div class="card-header"><i class="bi bi-credit-card me-2 text-warning"></i>Paiements</div>
            <div class="card-body p-0">
                @if($contrat->payments->isEmpty())
                    <div class="text-center py-3" style="color:var(--text-muted); font-size:.875rem;">Aucun paiement</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sunu mb-0">
                            <thead><tr><th>Référence</th><th>Méthode</th><th>Montant</th><th>Date</th><th>Statut</th></tr></thead>
                            <tbody>
                                @foreach($contrat->payments as $p)
                                <tr>
                                    <td style="font-size:.8rem; font-weight:600;">{{ $p->reference }}</td>
                                    <td style="font-size:.8rem;">{{ match($p->methode) { 'wave'=>'🔵 Wave','orange_money'=>'🟠 Orange Money','simulation'=>'🧪 Simulation', default=>$p->methode } }}</td>
                                    <td style="font-weight:700; font-size:.82rem; color:var(--orange);">{{ number_format($p->montant, 0, ',', ' ') }} XOF</td>
                                    <td style="font-size:.8rem;">{{ $p->paye_le?->format('d/m/Y') ?? '—' }}</td>
                                    <td>
                                        @php $pm=['succes'=>'badge-actif','en_attente'=>'badge-attente','echec'=>'badge-resilié']; @endphp
                                        <span class="badge-statut {{ $pm[$p->statut] ?? '' }}">{{ ucfirst($p->statut) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sinistres liés --}}
        <div class="card-sunu card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Sinistres liés</span>
            </div>
            <div class="card-body p-0">
                @if($contrat->sinistres->isEmpty())
                    <div class="text-center py-3" style="color:var(--text-muted); font-size:.875rem;">
                        <i class="bi bi-shield-check" style="color:#38A169;"></i> Aucun sinistre
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sunu mb-0">
                            <thead><tr><th>N°</th><th>Date</th><th>Réclamé</th><th>Accordé</th><th>Statut</th><th></th></tr></thead>
                            <tbody>
                                @foreach($contrat->sinistres as $s)
                                <tr>
                                    <td style="font-size:.8rem; font-weight:600;">{{ $s->numero_sinistre }}</td>
                                    <td style="font-size:.8rem;">{{ $s->date_sinistre?->format('d/m/Y') }}</td>
                                    <td style="font-size:.82rem;">{{ $s->montant_reclame ? number_format($s->montant_reclame,0,',',' ').' XOF' : '—' }}</td>
                                    <td style="font-size:.82rem; font-weight:700; color:#38A169;">{{ $s->montant_accorde ? number_format($s->montant_accorde,0,',',' ').' XOF' : '—' }}</td>
                                    <td>
                                        @php $sm=['declare'=>'badge-declare','en_instruction'=>'badge-instruct','accepte'=>'badge-accepte','refuse'=>'badge-refuse','indemnise'=>'badge-indemnise']; @endphp
                                        <span class="badge-statut {{ $sm[$s->statut] ?? '' }}">{{ ucfirst(str_replace('_',' ',$s->statut)) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('agent.sinistres.show', $s) }}"
                                           class="btn btn-sm" style="background:#F4F6F9; border-radius:8px; font-size:.75rem;">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Colonne droite --}}
    <div class="col-md-4">
        <div class="card-sunu card mb-3" style="background:var(--dark);">
            <div class="card-body text-center">
                <p style="color:rgba(255,255,255,.5); font-size:.78rem; margin-bottom:.25rem;">Prime</p>
                <h2 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; color:var(--orange); font-size:2rem; margin-bottom:.25rem;">
                    {{ number_format($contrat->prime, 0, ',', ' ') }}
                    <span style="font-size:.9rem; color:rgba(255,255,255,.5);">XOF</span>
                </h2>
                <p style="color:rgba(255,255,255,.4); font-size:.78rem;">/ {{ $contrat->periodicite }}</p>
            </div>
        </div>

        <div class="card-sunu card">
            <div class="card-header"><i class="bi bi-shield me-2 text-warning"></i>Couverture</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span style="font-size:.82rem; color:var(--text-muted);">Plafond</span>
                    <span style="font-weight:700; font-size:.875rem;">{{ number_format($contrat->assurance->montant_couverture ?? 0, 0, ',', ' ') }} XOF</span>
                </div>
                @if($contrat->assurance?->garanties)
                    <div style="font-size:.75rem; color:var(--text-muted); margin-top:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; margin-bottom:.4rem;">Garanties</div>
                    @foreach($contrat->assurance->garanties as $g)
                        <div style="font-size:.8rem; display:flex; align-items:center; gap:.4rem; margin-bottom:.3rem;">
                            <i class="bi bi-check-circle-fill" style="color:var(--orange); font-size:.75rem;"></i> {{ $g }}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
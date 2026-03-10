@extends('layouts.dashboard')
@section('title', 'Sinistre ' . $sinistre->numero_sinistre)
@section('page-title', 'Instruction du Sinistre')
@section('page-subtitle', $sinistre->numero_sinistre)

@section('content')

<div class="mb-3">
    <a href="{{ route('agent.sinistres.index') }}"
       style="color:var(--text-muted); font-size:.875rem; text-decoration:none; font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Retour aux sinistres
    </a>
</div>

<div class="row g-3">
    <div class="col-md-8">

        {{-- En-tête --}}
        <div class="card-sunu card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:.25rem;">
                            {{ $sinistre->numero_sinistre }}
                        </h5>
                        <span style="font-size:.8rem; color:var(--text-muted);">
                            Déclaré le {{ $sinistre->created_at?->format('d/m/Y à H:i') }}
                        </span>
                    </div>
                    <div class="d-flex gap-2">
                        @php $sm=['declare'=>'badge-declare','en_instruction'=>'badge-instruct','accepte'=>'badge-accepte','refuse'=>'badge-refuse','indemnise'=>'badge-indemnise']; @endphp
                        <span class="badge-statut {{ $sm[$sinistre->statut] ?? '' }}" style="font-size:.82rem;">
                            {{ ucfirst(str_replace('_',' ',$sinistre->statut)) }}
                        </span>
                        <a href="{{ route('agent.sinistres.edit', $sinistre) }}" class="btn-orange btn btn-sm">
                            <i class="bi bi-pencil me-1"></i> Instruire
                        </a>
                    </div>
                </div>

                <hr style="border-color:var(--gray-border); margin:1.25rem 0;">

                <div class="row g-3 mb-3">
                    @foreach([
                        ['Date du sinistre', $sinistre->date_sinistre?->format('d/m/Y')],
                        ['Lieu',             $sinistre->lieu ?? 'Non renseigné'],
                        ['Montant réclamé',  $sinistre->montant_reclame ? number_format($sinistre->montant_reclame,0,',',' ').' XOF' : '—'],
                        ['Montant accordé',  $sinistre->montant_accorde ? number_format($sinistre->montant_accorde,0,',',' ').' XOF' : 'En attente'],
                    ] as [$label, $val])
                    <div class="col-6 col-md-3">
                        <div style="font-size:.75rem; color:var(--text-muted); margin-bottom:.2rem;">{{ $label }}</div>
                        <div style="font-weight:600; font-size:.875rem;">{{ $val }}</div>
                    </div>
                    @endforeach
                </div>

                <div style="background:#F8F9FA; border-radius:12px; padding:1rem;">
                    <div style="font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted); margin-bottom:.5rem;">Description</div>
                    <p style="font-size:.875rem; color:var(--text-dark); margin:0; line-height:1.6;">
                        {{ $sinistre->description }}
                    </p>
                </div>

                {{-- Documents --}}
                @if($sinistre->documents && count($sinistre->documents) > 0)
                <div class="mt-3">
                    <div style="font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--text-muted); margin-bottom:.5rem;">
                        Documents joints ({{ count($sinistre->documents) }})
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($sinistre->documents as $doc)
                        <a href="{{ asset('storage/'.$doc) }}" target="_blank"
                           style="background:#F4F6F9; border:1px solid var(--gray-border); border-radius:8px; padding:.4rem .75rem; font-size:.78rem; text-decoration:none; color:var(--text-dark); display:flex; align-items:center; gap:.4rem;">
                            <i class="bi bi-paperclip" style="color:var(--orange);"></i>
                            {{ basename($doc) }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Notes agent --}}
        @if($sinistre->notes_agent)
        <div class="card-sunu card">
            <div class="card-header"><i class="bi bi-chat-left-text me-2 text-warning"></i>Notes de l'agent</div>
            <div class="card-body">
                <p style="font-size:.875rem; color:var(--text-dark); margin:0; line-height:1.6;">
                    {{ $sinistre->notes_agent }}
                </p>
            </div>
        </div>
        @endif

    </div>

    {{-- Colonne droite --}}
    <div class="col-md-4">

        {{-- Client --}}
        <div class="card-sunu card mb-3">
            <div class="card-header"><i class="bi bi-person me-2 text-warning"></i>Client</div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:44px; height:44px; background:var(--orange-light); border-radius:12px; display:flex; align-items:center; justify-content:center; font-weight:700; color:var(--orange);">
                        {{ strtoupper(substr($sinistre->client->prenom??'C',0,1)) }}{{ strtoupper(substr($sinistre->client->nom??'L',0,1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:.875rem;">{{ $sinistre->client->nom_complet ?? '—' }}</div>
                        <div style="font-size:.78rem; color:var(--text-muted);">{{ $sinistre->client->email ?? '—' }}</div>
                    </div>
                </div>
                <div style="font-size:.82rem; color:var(--text-muted); display:flex; align-items:center; gap:.4rem;">
                    <i class="bi bi-phone" style="color:var(--orange);"></i>
                    {{ $sinistre->client->telephone ?? 'Non renseigné' }}
                </div>
            </div>
        </div>

        {{-- Contrat lié --}}
        <div class="card-sunu card mb-3">
            <div class="card-header"><i class="bi bi-file-earmark-text me-2 text-warning"></i>Contrat lié</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-1">
                    <span style="font-size:.8rem; color:var(--text-muted);">N° Contrat</span>
                    <span style="font-size:.82rem; font-weight:700;">{{ $sinistre->contrat->numero_contrat ?? '—' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span style="font-size:.8rem; color:var(--text-muted);">Assurance</span>
                    <span style="font-size:.82rem; font-weight:600;">{{ $sinistre->contrat->assurance->badge_type ?? '—' }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span style="font-size:.8rem; color:var(--text-muted);">Couverture</span>
                    <span style="font-size:.82rem; font-weight:700;">
                        {{ number_format($sinistre->contrat->assurance->montant_couverture??0,0,',',' ') }} XOF
                    </span>
                </div>
            </div>
        </div>

        {{-- Taux remboursement --}}
        @if($sinistre->taux_remboursement)
        <div class="card-sunu card" style="background:var(--dark);">
            <div class="card-body text-center">
                <p style="color:rgba(255,255,255,.5); font-size:.78rem; margin-bottom:.25rem;">Taux de remboursement</p>
                <h2 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; color:var(--orange); font-size:2.5rem; margin:0;">
                    {{ $sinistre->taux_remboursement }}%
                </h2>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
@extends('layouts.dashboard')
@section('title', 'Sinistre ' . $sinistre->numero_sinistre)
@section('page-title', 'Détail du Sinistre')
@section('page-subtitle', $sinistre->numero_sinistre)

@section('content')

{{-- Alerte flash --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4">

    {{-- Colonne principale --}}
    <div class="col-lg-8">

        {{-- En-tête sinistre --}}
        <div class="card-sunu card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <div style="font-size:.78rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.35rem;">
                            Numéro de sinistre
                        </div>
                        <h4 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; color:var(--text-dark); margin:0;">
                            {{ $sinistre->numero_sinistre }}
                        </h4>
                    </div>
                    @php
                        $badges = [
                            'declare'        => ['bg-warning text-dark', 'Déclaré'],
                            'en_instruction' => ['bg-info text-white',   'En instruction'],
                            'accepte'        => ['bg-success text-white','Accepté'],
                            'refuse'         => ['bg-danger text-white', 'Refusé'],
                            'indemnise'      => ['bg-primary text-white','Indemnisé'],
                        ];
                        [$bclass, $blabel] = $badges[$sinistre->statut] ?? ['bg-secondary text-white', $sinistre->statut];
                    @endphp
                    <span class="badge {{ $bclass }}" style="font-size:.8rem; padding:.5rem 1rem; border-radius:20px;">
                        {{ $blabel }}
                    </span>
                </div>

                <hr style="border-color:var(--gray-border); margin:1.25rem 0;">

                <div class="row g-3">
                    <div class="col-sm-4">
                        <div style="font-size:.72rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; margin-bottom:.25rem;">Date du sinistre</div>
                        <div style="font-weight:700; font-size:.9rem;">
                            {{ $sinistre->date_sinistre?->format('d/m/Y') ?? '—' }}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:.72rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; margin-bottom:.25rem;">Lieu</div>
                        <div style="font-weight:600; font-size:.9rem;">{{ $sinistre->lieu ?: '—' }}</div>
                    </div>
                    <div class="col-sm-4">
                        <div style="font-size:.72rem; color:var(--text-muted); font-weight:600; text-transform:uppercase; margin-bottom:.25rem;">Déclaré le</div>
                        <div style="font-weight:600; font-size:.9rem;">{{ $sinistre->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="card-sunu card mb-4">
            <div class="card-body">
                <h6 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem;">
                    <i class="bi bi-file-text" style="color:var(--orange);"></i> Description
                </h6>
                <p style="color:var(--text-muted); font-size:.9rem; line-height:1.7; margin:0;">
                    {{ $sinistre->description }}
                </p>
            </div>
        </div>

        {{-- Documents --}}
        @if($sinistre->documents && count($sinistre->documents) > 0)
        <div class="card-sunu card mb-4">
            <div class="card-body">
                <h6 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem;">
                    <i class="bi bi-paperclip" style="color:var(--orange);"></i> Documents joints
                </h6>
                <div class="row g-2">
                    @foreach($sinistre->documents as $doc)
                    <div class="col-sm-6">
                        <a href="{{ asset('storage/' . $doc) }}" target="_blank"
                           style="display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem; border:1.5px solid var(--gray-border); border-radius:12px; text-decoration:none; color:var(--text-dark); transition:all .2s;"
                           onmouseover="this.style.borderColor='var(--orange)';this.style.background='var(--orange-light)'"
                           onmouseout="this.style.borderColor='var(--gray-border)';this.style.background='white'">
                            <i class="bi bi-file-earmark-arrow-down" style="color:var(--orange); font-size:1.3rem;"></i>
                            <span style="font-size:.82rem; font-weight:600; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ basename($doc) }}
                            </span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Notes de l'agent --}}
        @if($sinistre->notes_agent)
        <div class="card-sunu card mb-4">
            <div class="card-body">
                <h6 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem;">
                    <i class="bi bi-chat-left-text" style="color:var(--orange);"></i> Note de l'agent instructeur
                </h6>
                <div style="background:var(--gray-bg); border-radius:12px; padding:1rem; font-size:.875rem; color:var(--text-muted); line-height:1.7;">
                    {{ $sinistre->notes_agent }}
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Colonne latérale --}}
    <div class="col-lg-4">

        {{-- Montants --}}
        <div class="card-sunu card mb-4">
            <div class="card-body">
                <h6 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:1.25rem;">
                    💰 Montants
                </h6>

                <div style="display:flex; justify-content:space-between; align-items:center; padding:.75rem 0; border-bottom:1px solid var(--gray-border);">
                    <span style="font-size:.82rem; color:var(--text-muted);">Montant réclamé</span>
                    <span style="font-weight:700; font-size:.9rem;">
                        @if($sinistre->montant_reclame)
                            {{ number_format($sinistre->montant_reclame, 0, ',', ' ') }} XOF
                        @else
                            <span style="color:var(--text-muted);">—</span>
                        @endif
                    </span>
                </div>

                <div style="display:flex; justify-content:space-between; align-items:center; padding:.75rem 0;">
                    <span style="font-size:.82rem; color:var(--text-muted);">Montant accordé</span>
                    <span style="font-weight:800; font-size:1rem; color:{{ $sinistre->montant_accorde ? 'var(--orange)' : 'var(--text-muted)' }};">
                        @if($sinistre->montant_accorde)
                            {{ number_format($sinistre->montant_accorde, 0, ',', ' ') }} XOF
                        @else
                            En attente
                        @endif
                    </span>
                </div>
            </div>
        </div>

        {{-- Contrat lié --}}
        @if($sinistre->contrat)
        <div class="card-sunu card mb-4">
            <div class="card-body">
                <h6 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:1rem;">
                    📋 Contrat concerné
                </h6>
                <div style="font-size:.82rem; color:var(--text-muted); margin-bottom:.4rem;">Numéro</div>
                <div style="font-weight:700; margin-bottom:1rem;">{{ $sinistre->contrat->numero_contrat }}</div>

                @if($sinistre->contrat->assurance)
                <div style="font-size:.82rem; color:var(--text-muted); margin-bottom:.4rem;">Assurance</div>
                <div style="font-weight:600; margin-bottom:1rem;">{{ $sinistre->contrat->assurance->nom }}</div>
                @endif

                <a href="{{ route('client.contrats.show', $sinistre->contrat) }}"
                   style="display:block; text-align:center; padding:.6rem; border:1.5px solid var(--orange); color:var(--orange); border-radius:10px; font-weight:700; font-size:.82rem; text-decoration:none; transition:all .2s;"
                   onmouseover="this.style.background='var(--orange)';this.style.color='white'"
                   onmouseout="this.style.background='white';this.style.color='var(--orange)'">
                    Voir le contrat
                </a>
            </div>
        </div>
        @endif

        {{-- Agent instructeur --}}
        @if($sinistre->agent)
        <div class="card-sunu card mb-4">
            <div class="card-body">
                <h6 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:1rem;">
                    👤 Agent instructeur
                </h6>
                <div class="d-flex align-items-center gap-3">
                    <img src="{{ $sinistre->agent->photo_url }}" alt="agent"
                         style="width:44px; height:44px; border-radius:50%; object-fit:cover;">
                    <div>
                        <div style="font-weight:700; font-size:.9rem;">{{ $sinistre->agent->nom_complet }}</div>
                        <div style="font-size:.78rem; color:var(--text-muted);">{{ $sinistre->agent->zone_couverte ?? 'Agent' }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Retour --}}
        <a href="{{ route('client.sinistres.index') }}"
           style="display:flex; align-items:center; gap:.5rem; color:var(--text-muted); font-size:.875rem; text-decoration:none; font-weight:600;"
           onmouseover="this.style.color='var(--orange)'"
           onmouseout="this.style.color='var(--text-muted)'">
            <i class="bi bi-arrow-left"></i> Retour à mes sinistres
        </a>

    </div>
</div>

@endsection
@extends('layouts.dashboard')
@section('title', 'Instruire Sinistre')
@section('page-title', 'Instruction du Sinistre')
@section('page-subtitle', $sinistre->numero_sinistre)

@section('content')

<div class="mb-3">
    <a href="{{ route('agent.sinistres.show', $sinistre) }}"
       style="color:var(--text-muted); font-size:.875rem; text-decoration:none; font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Retour
    </a>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card-sunu card">
            <div class="card-header"><i class="bi bi-pencil-square me-2 text-warning"></i>Décision sur le sinistre</div>
            <div class="card-body">
                <form method="POST" action="{{ route('agent.sinistres.update', $sinistre) }}">
                    @csrf
                    @method('PUT')

                    {{-- Récap sinistre --}}
                    <div class="p-3 mb-4" style="background:#F8F9FA; border-radius:12px;">
                        <div class="row g-2">
                            <div class="col-6">
                                <div style="font-size:.75rem; color:var(--text-muted);">Client</div>
                                <div style="font-weight:600; font-size:.875rem;">{{ $sinistre->client->nom_complet ?? '—' }}</div>
                            </div>
                            <div class="col-6">
                                <div style="font-size:.75rem; color:var(--text-muted);">Montant réclamé</div>
                                <div style="font-weight:700; color:var(--orange); font-size:.875rem;">
                                    {{ $sinistre->montant_reclame ? number_format($sinistre->montant_reclame,0,',',' ').' XOF' : '—' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Décision <span style="color:var(--orange);">*</span>
                        </label>
                        <div class="d-flex flex-column gap-2">
                            @foreach([
                                'en_instruction' => ['🔍', 'En cours d\'instruction', 'Dossier en cours d\'examen'],
                                'accepte'        => ['✅', 'Accepté', 'Sinistre validé, indemnisation à prévoir'],
                                'refuse'         => ['❌', 'Refusé', 'Sinistre ne correspond pas aux garanties'],
                                'indemnise'      => ['💰', 'Indemnisé', 'Paiement effectué au client'],
                            ] as $val => [$icon, $label, $desc])
                            <label style="border:2px solid var(--gray-border); border-radius:10px; padding:.875rem; cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:.75rem;"
                                   onclick="this.parentElement.querySelectorAll('label').forEach(l=>{l.style.borderColor='var(--gray-border)';l.style.background='white'}); this.style.borderColor='var(--orange)'; this.style.background='var(--orange-light)'">
                                <input type="radio" name="statut" value="{{ $val }}" {{ $sinistre->statut===$val?'checked':'' }} style="display:none;">
                                <span style="font-size:1.2rem;">{{ $icon }}</span>
                                <div>
                                    <div style="font-weight:700; font-size:.875rem;">{{ $label }}</div>
                                    <div style="font-size:.75rem; color:var(--text-muted);">{{ $desc }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('statut')<div class="text-danger mt-1" style="font-size:.8rem;">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Montant accordé (XOF)
                        </label>
                        <div class="input-group">
                            <input type="number" name="montant_accorde" class="form-control"
                                   value="{{ old('montant_accorde', $sinistre->montant_accorde) }}"
                                   placeholder="0" min="0"
                                   max="{{ $sinistre->montant_reclame ?? 9999999 }}">
                            <span class="input-group-text" style="background:#F7FAFC; border-color:#E2E8F0;">XOF</span>
                        </div>
                        <small style="color:var(--text-muted); font-size:.75rem;">
                            Laissez vide si le dossier est encore en instruction
                        </small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Notes / Motif de la décision
                        </label>
                        <textarea name="notes_agent" class="form-control" rows="4"
                                  placeholder="Expliquez la décision prise...">{{ old('notes_agent', $sinistre->notes_agent) }}</textarea>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('agent.sinistres.show', $sinistre) }}"
                           class="btn btn-outline-secondary" style="border-radius:10px;">Annuler</a>
                        <button type="submit" class="btn-orange btn">
                            <i class="bi bi-check2 me-1"></i> Enregistrer la décision
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info panel --}}
    <div class="col-md-5">
        <div class="card-sunu card mb-3">
            <div class="card-header"><i class="bi bi-file-text me-2 text-warning"></i>Description déclarée</div>
            <div class="card-body">
                <p style="font-size:.875rem; color:var(--text-dark); line-height:1.6; margin:0;">
                    {{ $sinistre->description }}
                </p>
                @if($sinistre->lieu)
                <div class="mt-2" style="font-size:.8rem; color:var(--text-muted);">
                    <i class="bi bi-geo-alt me-1" style="color:var(--orange);"></i>{{ $sinistre->lieu }}
                </div>
                @endif
                @if($sinistre->date_sinistre)
                <div class="mt-1" style="font-size:.8rem; color:var(--text-muted);">
                    <i class="bi bi-calendar me-1" style="color:var(--orange);"></i>{{ $sinistre->date_sinistre->format('d/m/Y') }}
                </div>
                @endif
            </div>
        </div>

        @if($sinistre->documents && count($sinistre->documents) > 0)
        <div class="card-sunu card">
            <div class="card-header"><i class="bi bi-paperclip me-2 text-warning"></i>Pièces jointes</div>
            <div class="card-body d-flex flex-wrap gap-2">
                @foreach($sinistre->documents as $doc)
                <a href="{{ asset('storage/'.$doc) }}" target="_blank"
                   style="background:#F4F6F9; border:1px solid var(--gray-border); border-radius:8px; padding:.4rem .75rem; font-size:.78rem; text-decoration:none; color:var(--text-dark); display:flex; align-items:center; gap:.4rem;">
                    <i class="bi bi-file-earmark" style="color:var(--orange);"></i>{{ basename($doc) }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
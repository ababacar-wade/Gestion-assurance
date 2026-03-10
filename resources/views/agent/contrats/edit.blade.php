@extends('layouts.dashboard')
@section('title', 'Modifier Contrat')
@section('page-title', 'Modifier le Contrat')
@section('page-subtitle', $contrat->numero_contrat)

@section('content')

<div class="mb-3">
    <a href="{{ route('agent.contrats.show', $contrat) }}"
       style="color:var(--text-muted); font-size:.875rem; text-decoration:none; font-weight:500;">
        <i class="bi bi-arrow-left me-1"></i> Retour au détail
    </a>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card-sunu card">
            <div class="card-header"><i class="bi bi-pencil me-2 text-warning"></i>Mise à jour du contrat</div>
            <div class="card-body">
                <form method="POST" action="{{ route('agent.contrats.update', $contrat) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Statut du contrat
                        </label>
                        <select name="statut" class="form-select" required>
                            @foreach(['en_attente' => 'En attente', 'actif' => 'Actif', 'suspendu' => 'Suspendu', 'resilié' => 'Résilié', 'expire' => 'Expiré'] as $val => $label)
                                <option value="{{ $val }}" {{ $contrat->statut === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight:600; font-size:.82rem; text-transform:uppercase; letter-spacing:.03em;">
                            Notes internes
                        </label>
                        <textarea name="notes" class="form-control" rows="4"
                                  placeholder="Ajoutez des notes sur ce contrat...">{{ old('notes', $contrat->notes) }}</textarea>
                    </div>

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('agent.contrats.show', $contrat) }}"
                           class="btn btn-outline-secondary" style="border-radius:10px;">Annuler</a>
                        <button type="submit" class="btn-orange btn">
                            <i class="bi bi-check2 me-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Récap --}}
    <div class="col-md-5">
        <div class="card-sunu card">
            <div class="card-header"><i class="bi bi-info-circle me-2 text-warning"></i>Récapitulatif</div>
            <div class="card-body">
                <div class="d-flex flex-column gap-2">
                    @foreach([
                        ['N° Contrat',  $contrat->numero_contrat],
                        ['Client',      $contrat->client->nom_complet ?? '—'],
                        ['Assurance',   $contrat->assurance->nom ?? '—'],
                        ['Prime',       number_format($contrat->prime,0,',',' ').' XOF'],
                        ['Début',       $contrat->date_debut?->format('d/m/Y')],
                        ['Fin',         $contrat->date_fin?->format('d/m/Y')],
                    ] as [$label, $val])
                    <div class="d-flex justify-content-between" style="font-size:.875rem; padding:.4rem 0; border-bottom:1px solid var(--gray-border);">
                        <span style="color:var(--text-muted);">{{ $label }}</span>
                        <span style="font-weight:600;">{{ $val }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
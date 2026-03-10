@extends('layouts.dashboard')
@section('title', 'Catalogue Assurances')
@section('page-title', 'Catalogue des Assurances')
@section('page-subtitle', 'Gérer les offres disponibles')

@section('content')

<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('admin.assurances.create') }}" class="btn-orange btn btn-sm">
        <i class="bi bi-plus me-1"></i> Nouvelle offre
    </a>
</div>

{{-- Groupées par type --}}
@foreach(['auto' => ['🚗', 'Auto'], 'habitat' => ['🏠', 'Habitat'], 'vie' => ['❤️', 'Vie']] as $type => [$emoji, $label])
@if(isset($assurances[$type]) && $assurances[$type]->count() > 0)
<div class="mb-4">
    <h6 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin-bottom:.875rem; display:flex; align-items:center; gap:.5rem;">
        <span style="font-size:1.1rem;">{{ $emoji }}</span> Assurance {{ $label }}
        <span style="background:#EDF2F7; color:var(--text-muted); font-size:.72rem; font-weight:700; padding:.2rem .6rem; border-radius:20px; margin-left:.25rem;">
            {{ $assurances[$type]->count() }}
        </span>
    </h6>
    <div class="row g-3">
        @foreach($assurances[$type] as $assurance)
        <div class="col-md-4">
            <div class="card-sunu card" style="{{ !$assurance->is_active ? 'opacity:.6;' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h6 style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; margin:0; font-size:.95rem;">
                            {{ $assurance->nom }}
                        </h6>
                        <div class="dropdown">
                            <button class="btn btn-sm" style="background:#F4F6F9; border-radius:8px;" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" style="border-radius:12px; border:1px solid var(--gray-border); box-shadow:0 8px 30px rgba(0,0,0,.1);">
                                <li><a class="dropdown-item" href="{{ route('admin.assurances.edit', $assurance) }}" style="font-size:.875rem;">
                                    <i class="bi bi-pencil me-2 text-warning"></i>Modifier
                                </a></li>
                                <li>
                                    <form method="POST" action="{{ route('admin.assurances.destroy', $assurance) }}"
                                          onsubmit="return confirm('Supprimer cette offre ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger" style="font-size:.875rem;">
                                            <i class="bi bi-trash me-2"></i>Supprimer
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <p style="font-size:.8rem; color:var(--text-muted); margin-bottom:1rem; line-height:1.5;">
                        {{ Str::limit($assurance->description, 80) }}
                    </p>

                    <div class="d-flex gap-3 mb-2">
                        <div>
                            <div style="font-size:.7rem; color:var(--text-muted);">Mensuel</div>
                            <div style="font-weight:800; color:var(--orange); font-size:.95rem; font-family:'Plus Jakarta Sans',sans-serif;">
                                {{ number_format($assurance->prix_mensuel,0,',',' ') }} XOF
                            </div>
                        </div>
                        <div>
                            <div style="font-size:.7rem; color:var(--text-muted);">Annuel</div>
                            <div style="font-weight:700; font-size:.875rem;">
                                {{ number_format($assurance->prix_annuel,0,',',' ') }} XOF
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span style="font-size:.75rem; color:var(--text-muted);">
                            Couverture : {{ number_format($assurance->montant_couverture,0,',',' ') }} XOF
                        </span>
                        @if($assurance->is_active)
                            <span class="badge-statut badge-actif">Actif</span>
                        @else
                            <span class="badge-statut badge-expire">Inactif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endforeach

@endsection
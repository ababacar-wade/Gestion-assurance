@extends('layouts.dashboard')
@section('title', 'Instruire sinistre')
@section('page-title', 'Instruction du sinistre')
@section('page-subtitle', $sinistre->numero_sinistre)
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card-sunu card">
  <div class="card-body p-4">
    <div style="background:var(--gray-bg);border-radius:12px;padding:1rem;margin-bottom:1.5rem;font-size:.875rem;color:var(--text-muted);">
        <strong style="color:var(--text-dark);">Description :</strong> {{ Str::limit($sinistre->description, 150) }}
    </div>
    <form method="POST" action="{{ route('admin.sinistres.update', $sinistre) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Statut</label>
            <select name="statut" style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
                @foreach(['declare'=>'Déclaré','en_instruction'=>'En instruction','accepte'=>'Accepté','refuse'=>'Refusé','indemnise'=>'Indemnisé'] as $val => $label)
                <option value="{{ $val }}" {{ $sinistre->statut===$val?'selected':'' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Montant accordé (XOF)</label>
            <input type="number" name="montant_accorde" value="{{ old('montant_accorde', $sinistre->montant_accorde) }}" min="0"
                   style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;"
                   placeholder="Laisser vide si non déterminé">
        </div>
        <div class="mb-3">
            <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Notes de l'agent</label>
            <textarea name="notes_agent" rows="4" style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;" placeholder="Observations, justification de la décision...">{{ old('notes_agent', $sinistre->notes_agent) }}</textarea>
        </div>
        <div class="d-flex gap-3">
            <a href="{{ route('admin.sinistres.show', $sinistre) }}" style="flex:1;text-align:center;padding:.65rem;border:1.5px solid var(--gray-border);border-radius:10px;color:var(--text-muted);font-weight:700;font-size:.875rem;text-decoration:none;">Annuler</a>
            <button type="submit" style="flex:2;padding:.65rem;background:var(--orange);color:white;border:none;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;cursor:pointer;">Enregistrer la décision</button>
        </div>
    </form>
  </div>
</div>
</div></div>
@endsection
@extends('layouts.dashboard')
@section('title', 'Modifier agent')
@section('page-title', 'Modifier l\'agent')
@section('page-subtitle', $agent->nom_complet)
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card-sunu card">
  <div class="card-body p-4">
    <form method="POST" action="{{ route('admin.agents.update', $agent) }}">
        @csrf @method('PUT')
        <div class="row g-3">
            @foreach([['nom','Nom',$agent->nom,'text'],['prenom','Prénom',$agent->prenom,'text'],['telephone','Téléphone',$agent->telephone,'text'],['zone_couverte','Zone couverte',$agent->zone_couverte,'text']] as [$name,$label,$val,$type])
            <div class="col-sm-6">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">{{ $label }}</label>
                <input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $val) }}"
                       style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
                @error($name)<div style="color:#DC2626;font-size:.78rem;margin-top:.25rem;">{{ $message }}</div>@enderror
            </div>
            @endforeach
            <div class="col-sm-6">
                <label style="font-size:.82rem;font-weight:600;color:var(--text-muted);display:block;margin-bottom:.4rem;">Statut</label>
                <select name="is_active" style="width:100%;padding:.65rem 1rem;border:1.5px solid var(--gray-border);border-radius:10px;font-size:.875rem;">
                    <option value="1" {{ $agent->is_active ? 'selected':'' }}>Actif</option>
                    <option value="0" {{ !$agent->is_active ? 'selected':'' }}>Inactif</option>
                </select>
            </div>
        </div>
        <div class="d-flex gap-3 mt-4">
            <a href="{{ route('admin.agents.show', $agent) }}" style="flex:1;text-align:center;padding:.65rem;border:1.5px solid var(--gray-border);border-radius:10px;color:var(--text-muted);font-weight:700;font-size:.875rem;text-decoration:none;">Annuler</a>
            <button type="submit" style="flex:2;padding:.65rem;background:var(--orange);color:white;border:none;border-radius:10px;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;cursor:pointer;">Enregistrer</button>
        </div>
    </form>
  </div>
</div>
</div></div>
@endsection
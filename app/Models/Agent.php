<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends User
{
    use HasFactory;

    protected $table = 'users';

    // NE PAS définir newFromBuilder() ici — hérité de User uniquement

    protected static function booted(): void
    {
        static::addGlobalScope('agent', fn (Builder $q) => $q->where('type', 'agent'));

        static::creating(function (Agent $model) {
            $model->type = 'agent';
        });
    }

    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class, 'agent_id');
    }

    public function sinistres(): HasMany
    {
        return $this->hasMany(Sinistre::class, 'agent_id');
    }

    public function getNbContratsActifsAttribute(): int
    {
        return $this->contrats()->where('statut', 'actif')->count();
    }

    public function scopeActifs(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends User
{
    /** @use HasFactory<\Database\Factories\AgentFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope('agent', fn (Builder $q) => $q->where('type', 'agent'));

        static::creating(function (Agent $model) {
            $model->type = 'agent';
        });
    }

    // ── Relations ─────────────────────────────────────────────────────────────

    /** Contrats dont cet agent est responsable */
    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class, 'agent_id');
    }

    /** Sinistres dont cet agent est chargé */
    public function sinistres(): HasMany
    {
        return $this->hasMany(Sinistre::class, 'agent_id');
    }

    // ── Accesseur : nombre de contrats actifs ─────────────────────────────────

    public function getNbContratsActifsAttribute(): int
    {
        return $this->contrats()->where('statut', 'actif')->count();
    }

    public static function scopeActifs(\Illuminate\Database\Eloquent\Builder $q): \Illuminate\Database\Eloquent\Builder
    {
        return $q->where('is_active', true);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends User
{
    use HasFactory;

    protected $table = 'users';

    // NE PAS définir newFromBuilder() ici — hérité de User uniquement

    protected static function booted(): void
    {
        static::addGlobalScope('client', fn (Builder $q) => $q->where('type', 'client'));

        static::creating(function (Client $model) {
            $model->type = 'client';
        });
    }

    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class, 'client_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'client_id');
    }

    public function sinistres(): HasMany
    {
        return $this->hasMany(Sinistre::class, 'client_id');
    }

    public function scopeActifs(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeAvecContratActif(Builder $q): Builder
    {
        return $q->whereHas('contrats', fn ($c) => $c->where('statut', 'actif'));
    }
}
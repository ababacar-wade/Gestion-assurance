<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auto extends Assurance
{
    /** @use HasFactory<\Database\Factories\AutoFactory> */
    use HasFactory;

    protected $table = 'assurances';

    protected static function booted(): void
    {
        static::addGlobalScope('auto', fn (Builder $q) => $q->where('type', 'auto'));
        static::creating(fn (Auto $m) => $m->type = 'auto');
    }

    /**
     * Calcule une prime estimée selon l'âge du véhicule et la formule choisie.
     */
    public function calculerPrime(int $anneeVehicule, string $formule = 'tiers'): float
    {
        $age = now()->year - $anneeVehicule;

        $base = match($formule) {
            'tous_risques'   => $this->prix_mensuel * 1.5,
            'tiers_etendu'   => $this->prix_mensuel * 1.2,
            default          => $this->prix_mensuel,
        };

        // Majoration si véhicule > 10 ans
        return $age > 10 ? $base * 1.15 : $base;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitat extends Assurance
{
    /** @use HasFactory<\Database\Factories\HabitatFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope('habitat', fn (Builder $q) => $q->where('type', 'habitat'));
        static::creating(fn (Habitat $m) => $m->type = 'habitat');
    }

    /**
     * Prime selon la surface habitable (+ 5 % par tranche de 20 m²).
     */
    public function calculerPrime(float $surface): float
    {
        $tranches = floor($surface / 20);
        return $this->prix_mensuel * (1 + ($tranches * 0.05));
    }
}

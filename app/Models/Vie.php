<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vie extends Assurance
{
    /** @use HasFactory<\Database\Factories\VieFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope('vie', fn (Builder $q) => $q->where('type', 'vie'));
        static::creating(fn (Vie $m) => $m->type = 'vie');
    }

    /**
     * Prime mensuelle ajustée selon l'âge du souscripteur.
     * Plus le client est âgé, plus la prime augmente.
     */
    public function calculerPrime(int $age): float
    {
        return match(true) {
            $age < 30 => $this->prix_mensuel,
            $age < 45 => $this->prix_mensuel * 1.2,
            $age < 60 => $this->prix_mensuel * 1.5,
            default   => $this->prix_mensuel * 2.0,
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assurance extends Model
{
    /** @use HasFactory<\Database\Factories\AssuranceFactory> */
    use HasFactory;

    protected $fillable = [
        'type', 'nom', 'description', 'prix_mensuel', 'prix_annuel',
        'montant_couverture', 'is_active', 'garanties',
        // auto
        'marque_vehicule', 'modele_vehicule', 'annee_vehicule',
        'type_vehicule', 'formule_auto',
        // habitat
        'type_habitat', 'surface_m2', 'couvre_contenu', 'couvre_structure',
        // vie
        'duree_annees', 'capital_garanti', 'age_min', 'age_max',
    ];

    protected $casts = [
        'garanties'       => 'array',
        'is_active'       => 'boolean',
        'prix_mensuel'    => 'decimal:2',
        'prix_annuel'     => 'decimal:2',
        'montant_couverture' => 'decimal:2',
        'couvre_contenu'  => 'boolean',
        'couvre_structure'=> 'boolean',
        'capital_garanti' => 'decimal:2',
    ];

    // ── STI ───────────────────────────────────────────────────────────────────

    protected static array $stiMap = [
        'auto'    => Auto::class,
        'habitat' => Habitat::class,
        'vie'     => Vie::class,
    ];

    public function newFromBuilder($attributes = [], $connection = null): static
    {

        $attributes = (array) $attributes; // correction

        $type  = $attributes['type'] ?? null;
        $class = static::$stiMap[$type] ?? static::class;

        $model = new $class();
        $model->exists = true;
        $model->setRawAttributes((array) $attributes, true);
        $model->setConnection($connection ?? $this->getConnectionName());
        $model->fireModelEvent('retrieved', false);

        return $model;
    }

    // ── Relations ─────────────────────────────────────────────────────────────

    public function contrats(): HasMany
    {
        return $this->hasMany(Contrat::class, 'assurance_id');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeActives(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    // ── Accesseur ─────────────────────────────────────────────────────────────

    public function getBadgeTypeAttribute(): string
    {
        return match($this->type) {
            'auto'    => ' Auto',
            'habitat' => ' Habitat',
            'vie'     => ' Vie',
            default   => $this->type,
        };
    }
}

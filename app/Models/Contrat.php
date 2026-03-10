<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Contrat extends Model
{
    /** @use HasFactory<\Database\Factories\ContratFactory> */
    use HasFactory;

    protected $fillable = [
        'client_id', 'assurance_id', 'agent_id', 'numero_contrat',
        'date_debut', 'date_fin', 'periodicite', 'prime', 'franchise',
        'statut', 'document_path', 'documents_supplementaires', 'notes',
    ];

    protected $casts = [
        'date_debut'               => 'date',
        'date_fin'                 => 'date',
        'prime'                    => 'decimal:2',
        'franchise'                => 'decimal:2',
        'documents_supplementaires'=> 'array',
    ];

    // Génération automatique du numéro de contrat
    protected static function booted(): void
    {
        static::creating(function (Contrat $contrat) {
            $contrat->numero_contrat ??= 'CTR-' . strtoupper(Str::random(8));
        });
    }

    // ── Relations ─────────────────────────────────────────────────────────────

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id')->withoutGlobalScopes();
    }

    public function assurance(): BelongsTo
    {
        return $this->belongsTo(Assurance::class, 'assurance_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'agent_id')->withoutGlobalScopes();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'contrat_id');
    }

    public function sinistres(): HasMany
    {
        return $this->hasMany(Sinistre::class, 'contrat_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function isActif(): bool
    {
        return $this->statut === 'actif' && $this->date_fin->isFuture();
    }

    public function getStatutBadgeAttribute(): string
    {
        return match($this->statut) {
            'actif'       => '<span class="badge bg-success">Actif</span>',
            'en_attente'  => '<span class="badge bg-warning">En attente</span>',
            'suspendu'    => '<span class="badge bg-secondary">Suspendu</span>',
            'resilié'     => '<span class="badge bg-danger">Résilié</span>',
            'expire'      => '<span class="badge bg-dark">Expiré</span>',
            default       => $this->statut,
        };
    }
}

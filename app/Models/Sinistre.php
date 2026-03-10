<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Sinistre extends Model
{
    /** @use HasFactory<\Database\Factories\SinistreFactory> */
    use HasFactory;

    protected $fillable = [
        'contrat_id', 'client_id', 'agent_id', 'numero_sinistre',
        'date_sinistre', 'lieu', 'description',
        'montant_reclame', 'montant_accorde', 'statut',
        'documents', 'notes_agent',
    ];

    protected $casts = [
        'date_sinistre'   => 'date',
        'montant_reclame' => 'decimal:2',
        'montant_accorde' => 'decimal:2',
        'documents'       => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Sinistre $s) {
            $s->numero_sinistre ??= 'SIN-' . strtoupper(Str::random(8));
        });
    }

    public function contrat(): BelongsTo  { return $this->belongsTo(Contrat::class); }
    public function client(): BelongsTo   { return $this->belongsTo(Client::class, 'client_id')->withoutGlobalScopes(); }
    public function agent(): BelongsTo    { return $this->belongsTo(Agent::class, 'agent_id')->withoutGlobalScopes(); }

    public function getTauxRemboursementAttribute(): ?float
    {
        if (!$this->montant_reclame || !$this->montant_accorde) return null;
        return round(($this->montant_accorde / $this->montant_reclame) * 100, 1);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'contrat_id', 'client_id', 'reference', 'transaction_id',
        'montant', 'devise', 'methode', 'numero_payeur',
        'statut', 'periode_debut', 'periode_fin', 'payload_retour', 'paye_le',
    ];

    protected $casts = [
        'montant'       => 'decimal:2',
        'payload_retour'=> 'array',
        'periode_debut' => 'date',
        'periode_fin'   => 'date',
        'paye_le'       => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Payment $p) {
            $p->reference ??= 'PAY-' . strtoupper(Str::random(10));
        });
    }

    public function contrat(): BelongsTo
    {
        return $this->belongsTo(Contrat::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id')->withoutGlobalScopes();
    }

    public function estSucces(): bool
    {
        return $this->statut === 'succes';
    }
}

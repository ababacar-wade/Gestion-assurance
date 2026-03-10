<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'type', 'nom', 'prenom', 'email', 'password',
        'telephone', 'adresse', 'photo', 'date_naissance',
        'cin', 'profession', 'matricule', 'zone_couverte', 'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_naissance'    => 'date',
            'is_active'         => 'boolean',
            'password'          => 'hashed',    
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // STI : Laravel instancie automatiquement la bonne sous-classe
    // selon la valeur de la colonne `type`.
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Map type → classe PHP.
     */
    protected static array $stiMap = [
        'admin'  => Admin::class,
        'agent'  => Agent::class,
        'client' => Client::class,
    ];

    /**
     * Surcharge de newFromBuilder : retourne le bon objet selon `type`.
     */
    public function newFromBuilder($attributes = [], $connection = null): static
    {
        $type  = $attributes['type'] ?? null;
        $class = static::$stiMap[$type] ?? static::class;

        /** @var static $model */
        $model = new $class();
        $model->exists = true;
        $model->setRawAttributes((array) $attributes, true);
        $model->setConnection($connection ?? $this->getConnectionName());
        $model->fireModelEvent('retrieved', false);

        return $model;
    }

     // ──────────────────────────────────────────────────────────────────────────
    // Accesseurs pratiques
    // ──────────────────────────────────────────────────────────────────────────

    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('images/default-avatar.png');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers de rôle
    // ──────────────────────────────────────────────────────────────────────────

    public function isAdmin(): bool   { return $this->type === 'admin';  }
    public function isAgent(): bool   { return $this->type === 'agent';  }
    public function isClient(): bool  { return $this->type === 'client'; }

    // ──────────────────────────────────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────────────────────────────────

    public function scopeActifs(\Illuminate\Database\Eloquent\Builder $q): \Illuminate\Database\Eloquent\Builder
    {
        return $q->where('is_active', true);
    }
}

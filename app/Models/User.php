<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nom', 'prenom', 'email', 'password', 'type',
        'telephone', 'adresse', 'photo', 'is_active',
        'date_naissance', 'cin', 'profession',
        'matricule', 'zone_couverte',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_naissance'    => 'date',
        'is_active'         => 'boolean',
        'password'          => 'hashed',
    ];

    // ── STI uniquement ici dans le parent ─────────────────────────────────
    // NE PAS surcharger newFromBuilder() dans les sous-classes !

    protected static array $stiMap = [
        'admin'  => Admin::class,
        'agent'  => Agent::class,
        'client' => Client::class,
    ];

    public function newFromBuilder($attributes = [], $connection = null)
    {
        // $attributes peut être un tableau OU un stdClass selon le driver
        $attrs = (array) $attributes;

        $type  = $attrs['type'] ?? null;
        $class = static::$stiMap[$type] ?? static::class;

        /** @var static $model */
        $model = new $class();
        $model->exists = true;
        $model->setRawAttributes($attrs, true);
        $model->setConnection($connection ?? $this->getConnectionName());
        $model->fireModelEvent('retrieved', false);

        return $model;
    }

    // ── Helpers rôle ──────────────────────────────────────────────────────

    public function isAdmin(): bool  { return $this->type === 'admin';  }
    public function isAgent(): bool  { return $this->type === 'agent';  }
    public function isClient(): bool { return $this->type === 'client'; }

    // ── Accesseurs ────────────────────────────────────────────────────────

    public function getNomCompletAttribute(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function getPhotoUrlAttribute(): string
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->nom_complet) . '&background=FF6B2B&color=fff';
    }
}
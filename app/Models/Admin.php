<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends User
{
    use HasFactory;

    protected $table = 'users';

    // NE PAS définir newFromBuilder() ici — hérité de User uniquement

    protected static function booted(): void
    {
        static::addGlobalScope('admin', fn (Builder $q) => $q->where('type', 'admin'));

        static::creating(function (Admin $model) {
            $model->type = 'admin';
        });
    }
}
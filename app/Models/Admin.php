<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends User
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::addGlobalScope('admin', fn (Builder $q) => $q->where('type', 'admin'));

        static::creating(function (Admin $model) {
            $model->type = 'admin';
        });
    }
}

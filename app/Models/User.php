<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'activo',
    ];

    /**
     * Atributos ocultos para la serialización.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts de atributos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    /**
     * Relación: Un usuario pertenece a un Rol (Departamento).
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relación: Un usuario puede registrar muchos pedidos.
     */
    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'user_id_registro');
    }
}
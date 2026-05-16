<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pedido extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'factura_num',
        'numero_cliente_unico',
        'nombre_cliente',
        'datos_fiscales',
        'direccion_entrega',
        'estado',
        'notas_extra',
        'user_id_registro'
    ];

    /**
     * Relación: Un pedido tiene muchas evidencias (fotos de carga/entrega)
     */
    public function evidencias(): HasMany
    {
        return $this->hasMany(Evidencia::class);
    }

    /**
     * Relación: Un pedido pertenece al usuario de Ventas que lo registró
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_registro');
    }
}
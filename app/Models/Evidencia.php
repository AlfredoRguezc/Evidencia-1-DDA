<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evidencia extends Model
{
    /**
     * Definimos el nombre de la tabla si es diferente al plural automático.
     * En la migración usamos 'evidencias'.
     */
    protected $table = 'evidencias';

    /**
     * Atributos asignables en masa.
     * 'tipo_evidencia' servirá para distinguir entre "carga" y "entrega".
     */
    protected $fillable = [
        'pedido_id',
        'url_foto',
        'tipo_evidencia',
    ];

    /**
     * Relación: Una evidencia pertenece a un solo pedido.
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }
}
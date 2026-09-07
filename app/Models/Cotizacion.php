<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cotizacion extends Model
{
    protected $table = "cotizaciones";

    protected $fillable = [
        'entorno_id',
        'generador_id',
        'equipos_json',
        'potencia_total_w',
        'capacidad_recomendada_kva',
        'capacidad_recomendada_kw',
        'nombre_cliente',
        'telefono',
        'email',
        'estado',
    ];

    protected $casts = [
        'equipos_json' => 'array',
        'capacidad_recomendada_kva' => 'decimal:2',
        'capacidad_recomendada_kw' => 'decimal:2',
    ];

    public function entorno(): BelongsTo
    {
        return $this->belongsTo(Entorno::class);
    }

    public function generador(): BelongsTo
    {
        return $this->belongsTo(Generador::class);
    }
}

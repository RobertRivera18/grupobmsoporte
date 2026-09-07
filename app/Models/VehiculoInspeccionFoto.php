<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehiculoInspeccionFoto extends Model
{
    protected $table = 'vehiculo_inspeccion_fotos';

    protected $fillable = [
        'vehiculo_inspeccion_id',
        'ruta_foto',
    ];

    public function inspeccion(): BelongsTo
    {
        return $this->belongsTo(VehiculoInspeccion::class, 'vehiculo_inspeccion_id');
    }
}
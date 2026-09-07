<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudDesvinculacionEquipo extends Model
{
    protected $table = "solicitud_desvinculacion_equipo";
    protected $fillable = [
        'solicitud_desvinculacion_id',
        'equipo_id',
        'destino',
        'observaciones',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudDesvinculacion::class, 'solicitud_desvinculacion_id');
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipos::class, 'equipo_id');
    }
}

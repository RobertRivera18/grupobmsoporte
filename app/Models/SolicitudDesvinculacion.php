<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SolicitudDesvinculacion extends Model
{
    protected $table = "solicitudes_desvinculacion";
    protected $fillable = [
        'user_id',
        'cuadrilla_id',
        'devolver_credencial',
        'devolver_uniforme',
        'observaciones',
        'etapa',
        'comprobante',
    ];

    protected $casts = [
        'devolver_credencial' => 'boolean',
        'devolver_uniforme'   => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    // Relación con la cuadrilla
    public function cuadrilla(): BelongsTo
    {
        return $this->belongsTo(Cuadrilla::class, 'cuadrilla_id');
    }

    // Relación con los equipos (detalles)
    public function equiposDetalle(): HasMany
    {
        return $this->hasMany(SolicitudDesvinculacionEquipo::class, 'solicitud_desvinculacion_id');
    }
}

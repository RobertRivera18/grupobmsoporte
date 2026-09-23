<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudDesvinculacionDescuento extends Model
{
    protected $table = 'solicitud_desvinculacion_descuentos';

    protected $fillable = [
        'solicitud_id',
        'concepto',
        'valor',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudDesvinculacion::class, 'solicitud_id');
    }
}

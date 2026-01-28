<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevolucionDetalleIndumentaria extends Model
{
    protected $table = 'devolucion_indumentaria_detalle';

    protected $fillable = [
        'devolucion_id',
        'indumentaria_id',
        'cantidad',
        'cantidad_reutilizable',
        'cantidad_baja',
    ];

    /**
     * Relación: pertenece a una devolución
     */
    public function devolucion()
    {
        return $this->belongsTo(
            DevolucionIndumentaria::class,
            'devolucion_id'
        );
    }

    /**
     * Relación: pertenece a una indumentaria
     */
    public function indumentaria()
    {
        return $this->belongsTo(
            Indumentaria::class,
            'indumentaria_id'
        );
    }
}

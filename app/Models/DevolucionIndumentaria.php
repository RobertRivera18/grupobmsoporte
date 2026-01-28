<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevolucionIndumentaria extends Model
{
    protected $table = 'devoluciones_indumentaria';

    protected $fillable = [
        'user_id',
        'ubicacion_id',
        'fecha_devolucion',
        'estado_uniforme',
        'observacion',
        'es_historica',
    ];
    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    /**
     * Relación: devolución ocurre en una ubicación
     */
    public function ubicacion()
    {
        return $this->belongsTo(
            Ubicacion::class,
            'ubicacion_id'
        );
    }

    /**
     * Relación: detalles de indumentaria devuelta
     */
    public function detalles()
    {
        return $this->hasMany(
            DevolucionDetalleIndumentaria::class,
            'devolucion_id'
        );
    }
}

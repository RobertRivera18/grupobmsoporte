<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalidaEquipo extends Model
{
    protected $table = 'salidas_equipos';
    protected $fillable = [
        'usuario_id',
        'equipo_id',
        'fecha_salida_solicitada',
        'fecha_retorno_estimada',
        'motivo',
        'estado',
        'aprobado_por',
        'fecha_aprobacion',
        'fecha_entrega',
        'fecha_devolucion',
        'motivo_rechazo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function equipos()
    {
        return $this->belongsTo(Equipos::class, 'equipo_id');
    }

    public function aprobador()
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}

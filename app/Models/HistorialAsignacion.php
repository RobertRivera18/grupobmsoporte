<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialAsignacion extends Model
{
    protected $table = 'historial_asignaciones';

    protected $fillable = [
        'equipo_id',
        'user_id',
        'fecha_asignacion',
        'fecha_desasignacion',
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipos::class, 'equipo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

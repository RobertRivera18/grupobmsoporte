<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngresoIndumentaria extends Model
{
    protected $table = 'ingresos_indumentaria';

    protected $fillable = [
        'indumentaria_id',
        'ubicacion_id',
        'user_id',
        'cantidad',
        'observacion',
    ];

    public function indumentaria()
    {
        return $this->belongsTo(Indumentaria::class);
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

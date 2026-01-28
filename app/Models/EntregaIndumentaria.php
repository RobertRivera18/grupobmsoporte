<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntregaIndumentaria extends Model
{

    protected $table = 'entregas_indumentaria';

    protected $fillable = [
        'user_id',
        'ubicacion_id',
        'fecha_entrega',
        'observacion',
        'es_historica',
    ];

    public function empleado()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }
    public function detalles()
    {
        return $this->hasMany(EntregaDetalleIndumentaria::class, 'entrega_id');
    }
}

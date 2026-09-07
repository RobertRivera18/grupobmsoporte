<?php

namespace App\Models;

use App\Http\Controllers\Admin\RegistroMantenimientos;
use Illuminate\Database\Eloquent\Model;

class RegistroMantenimientoDetalle extends Model
{
    protected $table = 'mantenimiento_detalles';
    protected $fillable = [
        'registro_mantenimiento_id',
        'tipo_servicio_id',
        'descripcion',
    ];

    public function registroMantenimiento()
    {
        return $this->belongsTo(RegistroMantenimiento::class, 'registro_mantenimiento_id');
    }
    public function tipoServicio()
    {
        return $this->belongsTo(TipoServicio::class, 'tipo_servicio_id');
    }
}

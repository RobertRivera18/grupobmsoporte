<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuadrilla extends Model
{
    protected $fillable = [
        'cua_nombre',
        'cua_empresa',
        'cua_ciudad',
        'recargas'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipos::class, 'equipos_cuadrilla', 'cuadrilla_id', 'equipo_id');
    }

    
    public function reportes()
    {
        return $this->belongsToMany(Report::class, 'reporte_recarga_detalles', 'cuadrilla_id', 'reporte_id')
                    ->withPivot('valor_recarga', 'observacion')
                    ->withTimestamps();
    }
}

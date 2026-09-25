<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipos extends Model
{
    protected $fillable = [
        'nombre',
        'marca',
        'modelo',
        'serie',
        'user_id',
        'ciudad',
        'datos',
        'observacion',
        'tipo_equipo_id',

    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'equipos_user', 'equipo_id', 'user_id');
    }


    public function cuadrilla()
    {
        return $this->belongsToMany(Cuadrilla::class, 'equipos_cuadrilla', 'equipo_id', 'cuadrilla_id')
            ->withPivot(['id', 'fecha_inicio', 'fecha_fin', 'motivo_desasignacion'])
            ->withTimestamps();
    }
    public function cuadrillas()
    {
        return $this->belongsToMany(Cuadrilla::class, 'equipos_cuadrilla', 'equipo_id', 'cuadrilla_id')
            ->withPivot(['id', 'fecha_inicio', 'fecha_fin', 'motivo_desasignacion'])
            ->withTimestamps();
    }


    public function cuadrillaActiva()
    {
        return $this->cuadrilla()->wherePivotNull('fecha_fin');
    }
    public function tipoEquipo()
    {
        return $this->belongsTo(TipoEquipo::class, 'tipo_equipo_id');
    }


    public function salidas()
    {
        return $this->hasMany(SalidaEquipo::class);
    }

    public function solicitudesDesvinculacion()
    {
        return $this->hasMany(SolicitudDesvinculacionEquipo::class);
    }
}

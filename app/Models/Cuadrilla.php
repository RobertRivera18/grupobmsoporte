<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuadrilla extends Model
{
    protected $table = "cuadrillas";
    protected $fillable = [
        'cua_nombre',
        'cua_empresa',
        'cua_ciudad',
        'recargas',
        'grupo_id'
    ];

    // public function users()
    // {
    //     return $this->belongsToMany(User::class);
    // }

    public function users()
    {
        return $this->belongsToMany(User::class, 'cuadrilla_user', 'cuadrilla_id', 'user_id')
            ->withPivot(['id', 'fecha_inicio', 'fecha_fin', 'motivo_salida'])
            ->withTimestamps();
    }

    public function usersActivos()
    {
        return $this->users()->wherePivotNull('fecha_fin');
    }

    public function equipos()
    {
        return $this->belongsToMany(Equipos::class, 'equipos_cuadrilla', 'cuadrilla_id', 'equipo_id');
    }

    public function equiposActivos()
    {
        return $this->equipos()->wherePivotNull('fecha_fin');
    }


    public function reportes()
    {
        return $this->belongsToMany(Report::class, 'reporte_recarga_detalles', 'cuadrilla_id', 'reporte_id')
            ->withPivot('valor_recarga', 'observacion')
            ->withTimestamps();
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }
    public function tecnologias()
    {
        return $this->belongsToMany(Tecnologia::class, 'cuadrilla_tecnologia', 'cuadrilla_id', 'tecnologia_id')
            ->withTimestamps();
    }
    public function inventarios()
    {
        return $this->hasMany(InventarioControl::class, 'cuadrilla_id');
    }
}

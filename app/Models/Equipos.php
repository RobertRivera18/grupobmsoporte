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
        return $this->belongsToMany(Cuadrilla::class, 'equipos_cuadrilla', 'equipo_id', 'cuadrilla_id');
    }
    public function tipoEquipo()
    {
        return $this->belongsTo(TipoEquipo::class, 'tipo_equipo_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'nombre'
    ];


    public function indicadores()
    {
        return $this->hasMany(Indicador::class);
    }

    public function indicadores_anio()
    {
        return $this->hasMany(IndicadorAnio::class);
    }

    public function auditoriaProcesos()
    {
        return $this->hasMany(AuditoriaProceso::class,'area_id');
    }
}

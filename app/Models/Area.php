<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'nombre',
        'responsable_id',
    ];

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

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
        return $this->hasMany(AuditoriaProceso::class, 'area_id');
    }
}

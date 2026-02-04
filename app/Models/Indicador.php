<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    protected $fillable = [
        'area_id',
        'nombre',
        'forma_calculo',
        'frecuencia_id',
        'responsable_id',
    ];
    protected $table = 'indicadores';

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
    public function anios()
    {
        return $this->hasMany(IndicadorAnio::class, 'indicador_id');
    }

    public function frecuencia()
    {
        return $this->belongsTo(FrecuenciaIndicador::class, 'frecuencia_id');
    }
   
}

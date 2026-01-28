<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrecuenciaIndicador extends Model
{
    protected $table = 'frecuencias_indicadores';

    protected $fillable = ['nombre'];

    public function indicadores()
    {
        return $this->hasMany(Indicador::class, 'frecuencia_id');
    }
}

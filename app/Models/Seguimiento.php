<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $fillable = [
        'indicador_anio_id',
        'mes',
        'valor',
    ];

    public function anio()
    {
        return $this->belongsTo(IndicadorAnio::class, 'indicador_anio_id');
    }
}

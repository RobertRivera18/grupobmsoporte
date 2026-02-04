<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndicadorAnio extends Model
{
    protected $table = 'indicadores_anio';

    protected $fillable = [
        'indicador_id',
        'anio',
        'meta',
        'resultado_obtenido',
        'ultima_revision',
        'observacion',
    ];
    protected $casts = [
        'ultima_revision' => 'datetime',
    ];


    public function indicador()
    {
        return $this->belongsTo(Indicador::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'indicador_anio_id');
    }
    
    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class, 'indicador_anio_id');
    }
}

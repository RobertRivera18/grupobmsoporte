<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['mes', 'total', 'fecha_registro'];



    public function detalles()
    {
        return $this->hasMany(ReporteRecargaDetalle::class, 'reporte_id');
    }


    public function cuadrillas()
    {
        return $this->belongsToMany(Cuadrilla::class, 'reporte_recarga_detalles', 'reporte_id', 'cuadrilla_id')
            ->withPivot('valor_recarga', 'observacion')
            ->withTimestamps();
    }
}

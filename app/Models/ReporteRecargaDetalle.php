<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteRecargaDetalle extends Model
{
    use HasFactory;

    protected $table = 'reporte_recarga_detalles';
    protected $fillable = ['reporte_id', 'cuadrilla_id', 'valor_recarga', 'observacion'];

    public function cuadrilla()
    {
        return $this->belongsTo(Cuadrilla::class, 'cuadrilla_id');
    }

    public function reporte()
    {
        return $this->belongsTo(Report::class, 'reporte_id');
    }
}

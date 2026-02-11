<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformeAuditoria extends Model
{
    protected $table = 'informes_auditoria';
    protected $fillable = [
        'auditoria_proceso_id',
        'resumen',
        'descripcion',
        'evidencia',
    ];

    public function auditoriaProceso()
    {
        return $this->belongsTo(AuditoriaProceso::class);
    }
    public function noConformidades()
    {
        return $this->hasMany(
            InformeNoConformidad::class,
            'informe_auditoria_id'
        );
    }
}

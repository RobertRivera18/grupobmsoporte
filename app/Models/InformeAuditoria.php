<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformeAuditoria extends Model
{
    protected $fillable = [
        'auditoria_proceso_id',
        'descripcion',
        'evidencia',
    ];

    public function auditoriaProceso()
    {
        return $this->belongsTo(AuditoriaProceso::class);
    }
}

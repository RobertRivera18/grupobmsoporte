<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NormaISO extends Model
{
    protected $table = 'normas_iso';
    protected $fillable = [
        'codigo',
        'descripcion',
    ];

    public function auditoriaProcesos()
    {
        return $this->belongsToMany(
            AuditoriaProceso::class,
            'auditoria_norma',
            'norma_iso_id',
            'auditoria_proceso_id'
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaNorma extends Model
{
    protected $table = 'auditoria_norma';
    protected $fillable = [
        'auditoria_proceso_id',
        'norma_iso_id',
    ];

    public function auditoriaProceso()
    {
        return $this->belongsTo(AuditoriaProceso::class);
    }

    public function norma()
    {
        return $this->belongsTo(NormaIso::class, 'norma_iso_id');
    }
}

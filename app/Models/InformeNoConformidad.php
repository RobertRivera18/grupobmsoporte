<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformeNoConformidad extends Model
{
    protected $table = 'informe_no_conformidades';

    protected $fillable = [
    'informe_auditoria_id',
    'norma_iso_id',
    'descripcion',
    'evidencia',
    'tipo',
];
    public function informe()
{
    return $this->belongsTo(InformeAuditoria::class);
}

public function norma()
{
    return $this->belongsTo(NormaIso::class, 'norma_iso_id');
}
}

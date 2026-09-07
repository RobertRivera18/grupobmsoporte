<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidenteFoto extends Model
{
    protected $table = 'incidente_fotos';
    protected $fillable = [
        'incidente_id',
        'archivo'
    ];

    /**
     * Relación Inversa: Una foto pertenece a un incidente específico.
     */
    public function incidente()
    {
        return $this->belongsTo(Incidente::class, 'incidente_id');
    }
}

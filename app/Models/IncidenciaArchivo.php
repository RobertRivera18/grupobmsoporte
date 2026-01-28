<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidenciaArchivo extends Model
{
    protected $table = 'incidencia_archivos';

    protected $fillable = [
        'incidencia_id',
        'archivo',
        'tipo',
    ];

    public function incidencia()
    {
        return $this->belongsTo(Incidencia::class);
    }
}

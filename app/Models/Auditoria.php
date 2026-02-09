<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $fillable = [
        'anio',
        'fecha_inicio',
        'fecha_fin',
        'estado',
    ];
    public function procesos()
    {
        return $this->hasMany(AuditoriaProceso::class);
    }
}

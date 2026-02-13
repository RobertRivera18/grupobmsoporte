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
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];
    public function procesos()
    {
        return $this->hasMany(AuditoriaProceso::class);
    }
}

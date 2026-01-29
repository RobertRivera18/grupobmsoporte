<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActaFirmada extends Model
{
    protected $table = 'actas_firmadas';

    protected $fillable = [
        'cuadrilla_id',
        'tipo',
        'responsable_id',
        'receptor_id',
        'cedula_responsable',
        'cedula_receptor',
        'firma_responsable',
        'firma_receptor',
        'ruta_docx',
        'firmado_en',
    ];

    protected $casts = [
        'firmado_en' => 'datetime',
    ];

    // 🔗 Relaciones
    public function cuadrilla()
    {
        return $this->belongsTo(Cuadrilla::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function receptor()
    {
        return $this->belongsTo(User::class, 'receptor_id');
    }
}

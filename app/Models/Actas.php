<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actas extends Model
{
    protected $fillable = [
        'user_id',
        'empresa',
        'tipo_sangre',
        'tipo_contrato_id',
        'fecha_entrega',
        'ruta_firma',
        'imagen_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function tipoContrato()
    {
        return $this->belongsTo(TipoContrato::class, 'tipo_contrato_id');
    }
}

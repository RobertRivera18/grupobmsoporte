<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ubicacion;
use App\Models\TraspasoDetalleIndumentaria;

class TraspasoIndumentaria extends Model
{
    protected $table = 'traspasos_indumentaria';

    protected $fillable = [
        'ubicacion_origen_id',
        'ubicacion_destino_id',
        'fecha',
        'observacion'
    ];

    public function origen()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_origen_id');
    }

    public function destino()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_destino_id');
    }

    public function detalles()
    {
        return $this->hasMany(TraspasoDetalleIndumentaria::class, 'traspaso_id');
    }
}


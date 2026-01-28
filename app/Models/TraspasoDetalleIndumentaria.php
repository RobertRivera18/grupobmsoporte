<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TraspasoIndumentaria;
use App\Models\Indumentaria;

class TraspasoDetalleIndumentaria extends Model
{
    protected $table = 'traspaso_indumentaria_detalle';

    protected $fillable = [
        'traspaso_id',
        'indumentaria_id',
        'cantidad',
        'tipo_inventario',
    ];

    public function traspaso()
    {
        return $this->belongsTo(TraspasoIndumentaria::class);
    }

    public function indumentaria()
    {
        return $this->belongsTo(Indumentaria::class);
    }
}

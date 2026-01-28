<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntregaDetalleIndumentaria extends Model
{
    protected $table = 'entrega_indumentaria_detalle';

    protected $fillable = [
        'entrega_id',
        'indumentaria_id',
        'cantidad',
        'tipo_inventario'
    ];

    public function entrega()
    {
        return $this->belongsTo(EntregaIndumentaria::class, 'entrega_id');
    }

    public function indumentaria()
    {
        return $this->belongsTo(Indumentaria::class);
    }
}

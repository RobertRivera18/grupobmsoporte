<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioControl extends Model
{
    protected $table = 'inventario_control';
    protected $fillable = [
        'grupo_id',
        'tecnologia_id',
        'cuadrilla_id',
        'fecha_inventario',
        'observaciones'
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }


    public function tecnologia()
    {
        return $this->belongsTo(Tecnologia::class, 'tecnologia_id');
    }


    public function cuadrilla()
    {
        return $this->belongsTo(Cuadrilla::class, 'cuadrilla_id');
    }

    public function detalles()
    {
        return $this->hasMany(InventarioMaterialControl::class, 'inventario_control_id');
    }
}

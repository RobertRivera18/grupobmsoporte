<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioMaterialControl extends Model
{
    protected $table = 'inventario_material_control';

    protected $fillable = [
        'inventario_control_id',
        'material_id',
        'stock_final'
    ];


    public function inventarioControl()
    {
        return $this->belongsTo(InventarioControl::class, 'inventario_control_id');
    }


    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
    
}

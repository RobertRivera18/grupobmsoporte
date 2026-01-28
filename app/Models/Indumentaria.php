<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indumentaria extends Model
{
    protected $fillable = ['nombre', 'tipo', 'color', 'talla', 'image'];

    /* =============================
     | RELACIONES
     =============================*/

    // 🆕 Inventario nuevo
    public function inventarios()
    {
        return $this->hasMany(InventarioIndumentaria::class);
    }

    // ♻️ Inventario usado
    public function inventariosUsados()
    {
        return $this->hasMany(InventarioIndumentariaUsada::class);
    }

    public function entregasDetalle()
    {
        return $this->hasMany(EntregaDetalleIndumentaria::class);
    }

    public function devolucionesDetalle()
    {
        return $this->hasMany(
            DevolucionDetalleIndumentaria::class,
            'indumentaria_id'
        );
    }

    /* =============================
     | ACCESSORS (STOCK)
     =============================*/

    // 🆕 Total inventario nuevo
    public function getStockNuevoAttribute()
    {
        return $this->inventarios->sum('stock');
    }

    // ♻️ Total inventario usado
    public function getStockUsadoAttribute()
    {
        return $this->inventariosUsados->sum('stock');
    }

    // 📊 Total general
    public function getStockTotalAttribute()
    {
        return $this->stock_nuevo + $this->stock_usado;
    }
}

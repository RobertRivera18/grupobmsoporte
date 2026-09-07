<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Generador extends Model
{
    protected $table = "generadores";
    protected $fillable = [
        'nombre',
        'capacidad_kva',
        'capacidad_kw',
        'combustible',
        'precio',
        'imagen',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'capacidad_kva' => 'decimal:2',
        'capacidad_kw' => 'decimal:2',
        'precio' => 'decimal:2',
    ];

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    /**
     * Filtra por uno o varios combustibles. Si el arreglo viene vacío,
     * no aplica filtro (se muestran todos).
     */
    public function scopeCombustibleEn(Builder $query, array $combustibles): Builder
    {
        return count($combustibles) ? $query->whereIn('combustible', $combustibles) : $query;
    }

    public function scopeConCapacidadMinima(Builder $query, float $kva): Builder
    {
        return $query->where('capacidad_kva', '>=', $kva);
    }
}

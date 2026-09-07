<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entorno extends Model
{
    protected $table = "entornos";
    protected $fillable = [
        'nombre',
        'slug',
        'icono',
        'descripcion',
        'orden',
    ];

    /**
     * Catálogo de equipos sugeridos para este entorno, con sus cantidades
     * y estado de selección por defecto (pivot entorno_equipo).
     */
    public function equipos(): BelongsToMany
    {
        return $this->belongsToMany(
            EquipoElectrico::class,
            'entorno_equipo',
            'entorno_id',
            'equipo_id'
        )
            ->withPivot([
                'cantidad_defecto',
                'seleccionado_defecto',
                'orden'
            ])
            ->withTimestamps()
            ->orderBy('entorno_equipo.orden');
    }

    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class);
    }
}

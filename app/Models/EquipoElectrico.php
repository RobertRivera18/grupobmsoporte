<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EquipoElectrico extends Model
{
    protected $table = 'equiposelectricos';
    protected $fillable = [
        'nombre',
        'icono',
        'potencia_nominal_w',
        'potencia_arranque_w',
        'es_motor',
    ];

    protected $casts = [
        'es_motor' => 'boolean',
        'potencia_nominal_w' => 'integer',
        'potencia_arranque_w' => 'integer',
    ];

    public function entornos(): BelongsToMany
    {
        return $this->belongsToMany(
            Entorno::class,
            'entorno_equipo',
            'equipo_id',
            'entorno_id'
        )
            ->withPivot([
                'cantidad_defecto',
                'seleccionado_defecto',
                'orden'
            ])
            ->withTimestamps();
    }

    /**
     * Watts extra que este equipo exige al arrancar, por encima de su
     * consumo nominal. 0 si no es un equipo con motor (no tiene pico).
     */
    public function getSurgeExtraWattsAttribute(): int
    {
        if (! $this->es_motor || ! $this->potencia_arranque_w) {
            return 0;
        }

        return max(0, $this->potencia_arranque_w - $this->potencia_nominal_w);
    }
}

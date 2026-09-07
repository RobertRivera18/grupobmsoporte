<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class RegistroMantenimiento extends Model
{
    protected $table = 'registro_mantenimientos';
    protected $fillable = [
        'vehiculo_id',
        'kilometraje',
        'fecha',
        'observaciones',
    ];


    protected $casts = [
        'fecha' => 'date',
        'kilometraje' => 'integer',

    ];


    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }


    public function detalles()
    {
        return $this->hasMany(RegistroMantenimientoDetalle::class, 'registro_mantenimiento_id');
    }
    public function scopeConRelaciones(Builder $query): Builder
    {
        return $query->with([
            'vehiculo',
            'detalles.tipoServicio'
        ]);
    }

    public function scopeBuscar(Builder $query, ?string $search): Builder
    {
        return $query->when(
            filled($search),
            function (Builder $query) use ($search) {

                $query->whereHas('vehiculo', function (Builder $vehiculo) use ($search) {

                    $vehiculo
                        ->where('placa', 'like', "%{$search}%")
                        ->orWhere('marca', 'like', "%{$search}%")
                        ->orWhere('modelo', 'like', "%{$search}%");
                });
            }
        );
    }

    public function scopeVehiculo(Builder $query, $vehiculoId): Builder
    {
        return $query->when(
            filled($vehiculoId),
            fn(Builder $query) => $query->where('vehiculo_id', $vehiculoId)
        );
    }

    public function scopeFecha(Builder $query, $fecha): Builder
    {
        return $query->when(
            filled($fecha),
            fn(Builder $query) => $query->whereDate('fecha', $fecha)
        );
    }

    public function scopeRangoFechas(
        Builder $query,
        $desde,
        $hasta
    ): Builder {

        return $query
            ->when(
                filled($desde),
                fn(Builder $query) => $query->whereDate('fecha', '>=', $desde)
            )
            ->when(
                filled($hasta),
                fn(Builder $query) => $query->whereDate('fecha', '<=', $hasta)
            );
    }

    public function scopeOrdenar(
        Builder $query,
        string $campo = 'fecha',
        string $direccion = 'desc'
    ): Builder {

        $permitidos = [
            'fecha',
            'kilometraje',
            'placa'
        ];

        if (! in_array($campo, $permitidos)) {
            $campo = 'fecha';
        }

        if ($campo === 'placa') {

            return $query
                ->join(
                    'vehiculos',
                    'vehiculos.id',
                    '=',
                    'registro_mantenimientos.vehiculo_id'
                )
                ->select('registro_mantenimientos.*')
                ->orderBy('vehiculos.placa', $direccion);
        }

        return $query->orderBy($campo, $direccion);
    }
}

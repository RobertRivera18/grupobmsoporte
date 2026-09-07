<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehiculoInspeccion extends Model
{
    protected $table = 'vehiculo_inspecciones';

    protected $fillable = [
        'vehiculo_id',
        'fecha',
        'tecnico_encargado_id',
        'kilometraje',
        'tipo_equipo',
        'checklist',
        'observaciones',
        'choques_golpes',
        'revisado_por_nombre',
        'tecnico_responsable_nombre',
        'recomendaciones_mantenimiento',
        'firma_path',
        'documento_firmado_path',
        'firmado_at',
    ];

    protected $casts = [
        'fecha' => 'date',
        'checklist' => 'array',
        'kilometraje' => 'integer',
        'firmado_at' => 'datetime',
    ];


    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class);
    }
    public function tecnicoEncargado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tecnico_encargado_id');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(VehiculoInspeccionFoto::class, 'vehiculo_inspeccion_id');
    }
}

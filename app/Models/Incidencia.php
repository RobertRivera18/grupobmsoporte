<?php

namespace App\Models;

use App\Enums\TipoIncidencia;
use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $fillable = [
        'nombre',
        'detalle',
        'fecha',
        'user_id'
    ];

    /**
     * Casts de atributos.
     */
    protected function casts(): array
    {
        return [
            'nombre' => TipoIncidencia::class,
            'fecha'  => 'date',
        ];
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function archivos()
    {
        return $this->hasMany(IncidenciaArchivo::class);
    }
}
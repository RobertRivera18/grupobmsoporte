<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidencia extends Model
{
    protected $fillable = [
        'nombre',
        'detalle',
        'fecha',
        'user_id'
    ];
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function archivos()
    {
        return $this->hasMany(IncidenciaArchivo::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incidente extends Model
{
    protected $table = 'incidentes';
    protected $fillable = [
        'vehiculo_id',
        'tipo',
        'fecha',
        'observaciones'
    ];

    public function choferes()
    {
        return $this->belongsToMany(User::class, 'incidente_chofer', 'incidente_id', 'user_id')
            ->withTimestamps();
    }
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }
    public function fotos()
    {
        return $this->hasMany(IncidenteFoto::class, 'incidente_id');
    }
}

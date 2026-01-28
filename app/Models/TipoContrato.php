<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoContrato extends Model
{
    protected $fillable = ['name'];
    
    public function actas()
    {
        return $this->hasMany(Actas::class, 'tipo_contrato_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tecnologia extends Model
{
    protected $table = 'tecnologias';

    protected $fillable = ['nombre'];

    public function materiales()
    {
        return $this->belongsToMany(Material::class, 'material_tecnologia', 'tecnologia_id', 'material_id');
    }
    
    public function tecnologias()
    {
        return $this->belongsToMany(Tecnologia::class, 'cuadrilla_tecnologia', 'cuadrilla_id', 'tecnologia_id')
            ->withTimestamps();
    }
}

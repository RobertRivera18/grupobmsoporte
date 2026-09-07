<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materiales';

    protected $fillable=['codigo','descripcion'];
    public function tecnologias()
    {
        return $this->belongsToMany(Tecnologia::class, 'material_tecnologia', 'material_id', 'tecnologia_id');
    }
}

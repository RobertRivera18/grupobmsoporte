<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NormaISO extends Model
{
    protected $table = 'normas_iso';
    protected $fillable = [
        'codigo',
        'descripcion',
    ];
}

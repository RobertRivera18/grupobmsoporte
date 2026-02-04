<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'comment',
        'rating',
        'user_id',
        'indicador_anio_id',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function indicadorAnio()
    {
        return $this->belongsTo(IndicadorAnio::class);
    }
}

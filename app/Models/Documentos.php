<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentos extends Model
{
    protected $fillable = [
        'tick_id',
        'doc_nombre',
    ];


    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'tick_id', 'tick_id');
    }
}

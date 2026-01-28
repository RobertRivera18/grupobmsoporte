<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketDetalle extends Model
{
    protected $table = 'tickectdetalle';
    protected $primaryKey = 'tickd_id';
    protected $fillable = [
        'tick_id',
        'usu_id',
        'tickd_descrip',
        'est',

    ];

     public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
{
    return $this->belongsTo(User::class, 'usu_id');
}
}


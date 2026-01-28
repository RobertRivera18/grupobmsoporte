<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'tick_id';
    
    protected $casts = [
        'fecha_asignacion' => 'datetime',
    ];

    protected $fillable = [
        'usu_id',
        'category_id',
        'tick_titulo',
        'tick_descrip',
        'tick_estado',
        'est',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usu_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function documentos()
    {
        return $this->hasMany(Documentos::class, 'tick_id', 'tick_id');
    }

    public function soporte()
    {
        return $this->belongsTo(User::class, 'soporte_asignado');
    }

   public function detalles()
{
    return $this->hasMany(TicketDetalle::class, 'tick_id');
}
}

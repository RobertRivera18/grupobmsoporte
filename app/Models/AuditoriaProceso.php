<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaProceso extends Model
{
    protected $table = 'auditoria_proceso';
    protected $fillable = [
        'auditoria_id',
        'area_id',
        'auditor_id',
        'responsable_id',
    ];

    public function auditoria()
    {
        return $this->belongsTo(Auditoria::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function normas()
    {
        return $this->belongsToMany(
            NormaISO::class,
            'auditoria_norma',
            'auditoria_proceso_id',
            'norma_iso_id'
        )->withTimestamps();
    }


    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
    
    public function informes()
    {
        return $this->hasMany(InformeAuditoria::class, 'auditoria_proceso_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditoriaProceso extends Model
{
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

    public function proceso()
    {
        return $this->belongsTo(Area::class);
    }

    public function auditoriaNormas()
    {
        return $this->hasMany(AuditoriaNorma::class);
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
}

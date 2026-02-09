<?php

namespace App\Livewire;

use App\Models\Auditoria;
use Livewire\Component;

class AuditoriasIndex extends Component
{
    public function render()
    {
        $auditorias=Auditoria::all();
        return view('livewire.auditorias-index',compact('auditorias'));
    }
}

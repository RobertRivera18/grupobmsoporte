<?php

namespace App\Livewire;

use App\Models\Auditoria;
use Livewire\Component;

class AuditoriasIndex extends Component
{
    public function eliminar($id)
    {
        try {

            $auditoria = Auditoria::findOrFail($id);

            $auditoria->delete();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'La auditoría fue eliminada correctamente.',
            ]);
        } catch (\Throwable $e) {

            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar la auditoría.',
            ]);
        }
    }

    public function render()
    {
        $auditorias = Auditoria::all();
        return view('livewire.auditorias-index', compact('auditorias'));
    }
}

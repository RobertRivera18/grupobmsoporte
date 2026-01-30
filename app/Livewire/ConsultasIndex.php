<?php

namespace App\Livewire;

use App\Models\ActaFirmada;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class ConsultasIndex extends Component
{
    /** BUSCADOR **/
    public string $search = '';
    public ?int $colaboradorId = null;
    public ?User $colaborador = null;

    /** RESULTADOS **/
    public Collection $actas;

    public function mount()
    {
        $this->actas = collect();
    }

    /* ===============================
     * SELECCIONAR COLABORADOR
     * =============================== */
    public function seleccionarColaborador(int $id): void
    {
        $this->colaboradorId = $id;
        $this->colaborador   = User::find($id);
        $this->reset('search');

        $this->cargarActas();
    }

    public function limpiarColaborador(): void
    {
        $this->reset(['colaboradorId', 'colaborador', 'search']);
        $this->actas = collect();
    }

    /* ===============================
     * BUSCADOR
     * =============================== */
    public function getColaboradoresProperty(): Collection
    {
        if (strlen($this->search) < 2) {
            return collect();
        }

        return User::where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('cedula', 'like', "%{$this->search}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'cedula']);
    }

    /* ===============================
     * CARGAR ACTAS
     * =============================== */
    public function cargarActas(): void
    {
        if (!$this->colaboradorId) {
            $this->actas = collect();
            return;
        }

        $this->actas = ActaFirmada::where(function ($q) {
                $q->where('responsable_id', $this->colaboradorId)
                  ->orWhere('receptor_id', $this->colaboradorId);
            })
            ->orderByDesc('firmado_en')
            ->get();
    }

    public function render()
    {
        return view('livewire.consultas-index');
    }
}

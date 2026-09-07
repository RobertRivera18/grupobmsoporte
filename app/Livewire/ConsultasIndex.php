<?php

namespace App\Livewire;

use App\Models\ActaFirmada;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use stdClass;

class ConsultasIndex extends Component
{
    /** BUSCADOR **/
    public string $search = '';
    public ?int $colaboradorId = null;
    public ?User $colaborador = null;

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
        if (!$this->colaboradorId || !$this->colaborador) {
            $this->actas = collect();
            return;
        }
        $actasCuadrilla = ActaFirmada::where(function ($q) {
                $q->where('responsable_id', $this->colaboradorId)
                  ->orWhere('receptor_id', $this->colaboradorId);
            })
            ->orderByDesc('firmado_en')
            ->get();

        $coleccionFinal = collect();
        if ($this->colaborador->ruta_firma && File::exists(public_path($this->colaborador->ruta_firma))) {
            $actaIndividual = new stdClass();
            $actaIndividual->id = 'user_' . $this->colaborador->id;
            $actaIndividual->tipo = 'Entrega Individual';
            $actaIndividual->ruta_archivo = $this->colaborador->ruta_firma;
            $actaIndividual->firmado_en = $this->colaborador->updated_at; // O la fecha de modificación del archivo
            $actaIndividual->es_individual = true;

            $coleccionFinal->push($actaIndividual);
        }

        $this->actas = $coleccionFinal->concat($actasCuadrilla);
    }
    public function render()
    {
        return view('livewire.consultas-index');
    }
}
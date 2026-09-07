<?php


namespace App\Livewire;

use App\Models\User;
use App\Models\Cuadrilla;
use App\Models\SolicitudDesvinculacion;
use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SolicitudDesvinculacionCreada;

class CrearDesvinculacion extends Component
{
    public string $search = '';
    public ?int $colaboradorId = null;
    public ?User $colaborador = null;
    public ?Cuadrilla $cuadrilla = null;
    public ?User $companero = null;

    public string $observaciones = '';
    public bool $devolverCredencial = false;
    public bool $devolverUniforme = false;

    public function seleccionarColaborador(int $id): void
    {
        $this->colaboradorId = $id;
        $this->colaborador = User::with(['cuadrillas.users'])->find($id);
        $this->cuadrilla   = $this->colaborador?->cuadrillas->first();
        $this->companero   = $this->cuadrilla?->users->where('id', '!=', $id)->first();
        $this->reset('search');
    }

    public function limpiarColaborador(): void
    {
        $this->reset(['colaboradorId', 'colaborador', 'cuadrilla', 'companero', 'search', 'observaciones', 'devolverCredencial', 'devolverUniforme']);
    }

    public function guardarSolicitudTTHH()
    {
        if (!$this->colaboradorId) {
            $this->dispatch('error', message: 'Debe seleccionar un colaborador.');
            return;
        }

        try {
            DB::transaction(function () {
                $solicitud = SolicitudDesvinculacion::create([
                    'user_id'             => $this->colaboradorId,
                    'cuadrilla_id'        => $this->cuadrilla?->id,
                    'devolver_credencial' => $this->devolverCredencial,
                    'devolver_uniforme'   => $this->devolverUniforme,
                    'observaciones'       => $this->observaciones,
                    'etapa'               => 'sistemas',
                ]);
                $solicitud->load('user');
                $admins = User::role('Admin')->get();

                if ($admins->isNotEmpty()) {
                    Notification::send($admins, new SolicitudDesvinculacionCreada($solicitud));
                }
            });

            session()->flash('success', 'Solicitud creada por TTHH. Pendiente de gestión por Sistemas.');
            return redirect()->route('admin.desvinculacion.index');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function getColaboradoresProperty(): Collection
    {
        if (strlen($this->search) < 2) {
            return collect();
        }

        return User::where('name', 'like', "%{$this->search}%")
            ->orWhere('cedula', 'like', "%{$this->search}%")
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'cedula']);
    }

    public function render()
    {
        return view('livewire.crear-desvinculacion');
    }
}

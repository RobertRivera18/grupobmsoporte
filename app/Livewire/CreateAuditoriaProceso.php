<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\Auditoria;
use App\Models\AuditoriaProceso;
use App\Models\NormaISO;
use App\Models\User;
use App\Notifications\AuditorAsignado;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateAuditoriaProceso extends Component
{
    use AuthorizesRequests;

    public Auditoria $auditoria;
    public $area_id;
    public $auditor_id;
    public $responsable_id;
    public $normas = [];
    public $procesos = [];

    private function refreshProcesos()
    {
        $this->procesos = $this->auditoria
            ->procesos()
            ->with(['area', 'auditor', 'responsable', 'normas'])
            ->get();
    }

    public function mount(Auditoria $auditoria)
    {
        $this->authorize('view', $auditoria);
        $this->auditoria = $auditoria;
        $this->refreshProcesos();
    }

    // 👇 AGREGAR ESTO
    public function updatedAreaId($value)
    {
        $area = Area::find($value);

        $this->responsable_id = $area?->responsable_id;
    }

    public function rules()
    {
        return [
            'area_id' => 'required|exists:areas,id',
            'auditor_id' => 'required|exists:users,id|different:responsable_id',
            'responsable_id' => 'required|exists:users,id|different:auditor_id',
            'normas' => 'required|array|min:1',
            'normas.*' => 'exists:normas_iso,id',
        ];
    }


    public function save()
{
    $this->validate();

    DB::transaction(function () {
        $proceso = AuditoriaProceso::create([
            'auditoria_id'   => $this->auditoria->id,
            'area_id'        => $this->area_id,
            'auditor_id'     => $this->auditor_id,
            'responsable_id' => $this->responsable_id,
        ]);

        $proceso->normas()->sync($this->normas);

        $proceso->load(['auditoria', 'area', 'auditor', 'responsable', 'normas']);

        if ($proceso->auditor) {
            $proceso->auditor->notify(new AuditorAsignado($proceso, 'auditor'));
        }

        if ($proceso->responsable && $proceso->responsable_id !== $proceso->auditor_id) {
            $proceso->responsable->notify(new AuditorAsignado($proceso, 'responsable'));
        }
    });

    $this->procesos = $this->auditoria
        ->procesos()
        ->with(['area', 'auditor', 'responsable', 'normas'])
        ->get();

    $this->reset(['area_id', 'auditor_id', 'responsable_id', 'normas']);

    $this->dispatch('swal', [
        'icon' => 'success',
        'title' => 'Proceso creado',
        'text' => 'El proceso de auditoría se creó correctamente.',
    ]);
}



    public function eliminar($id)
    {
        DB::transaction(function () use ($id) {

            $proceso = AuditoriaProceso::findOrFail($id);
            $proceso->normas()->detach();
            $proceso->delete();
        });

        $this->refreshProcesos();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Eliminado',
            'text' => 'El proceso de auditoría fue eliminado correctamente.',
        ]);
    }


    public function render()
    {
        return view('livewire.create-auditoria-proceso', [
            'areas' => Area::orderBy('nombre')->get(),
            'auditores' => User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['Admin', 'Auditor', 'LiderAuditorias']);
            })
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),
            'responsables' => User::all(),
            'normasIso' => NormaISO::orderBy('id')->get(),
        ]);
    }
}

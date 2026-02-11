<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\Auditoria;
use App\Models\AuditoriaNorma;
use App\Models\AuditoriaProceso;
use App\Models\NormaISO;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateAuditoriaProceso extends Component
{
    public Auditoria $auditoria;
    public $area_id;
    public $auditor_id;
    public $responsable_id;
    public $normas = [];
    public $procesos = [];



    public function mount(Auditoria $auditoria)
    {
        $this->auditoria = $auditoria;

        $this->procesos = $this->auditoria
            ->procesos()
            ->with(['area', 'auditor', 'responsable', 'normas'])
            ->get();
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

        if ($this->auditor_id === $this->responsable_id) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Validación',
                'text' => 'El auditor no puede ser el responsable del área.',
            ]);
            return;
        }

        DB::transaction(function () {
            $proceso = AuditoriaProceso::create([
                'auditoria_id'   => $this->auditoria->id,
                'area_id'        => $this->area_id,
                'auditor_id'     => $this->auditor_id,
                'responsable_id' => $this->responsable_id,
            ]);

            $proceso->normas()->sync($this->normas);
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
        DB::beginTransaction();

        try {
            $proceso = AuditoriaProceso::findOrFail($id);
            $proceso->normas()->detach();
            $proceso->delete();

            DB::commit();

            $this->procesos = $this->auditoria
                ->procesos()
                ->with(['area', 'auditor', 'responsable', 'normas'])
                ->get();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'El proceso de auditoría fue eliminado correctamente.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar el proceso.',
            ]);
        }
    }





    public function render()
    {
        return view('livewire.create-auditoria-proceso', [
            'areas' => Area::orderBy('nombre')->get(),
            'auditores' => User::role('Admin')->get(),
            'responsables' => User::all(),
            'normasIso' => NormaISO::orderBy('codigo')->get(),

        ]);
    }
}

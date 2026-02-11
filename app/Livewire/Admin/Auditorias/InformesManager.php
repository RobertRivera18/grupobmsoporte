<?php

namespace App\Livewire\Admin\Auditorias;

use App\Models\InformeAuditoria;
use App\Models\AuditoriaNorma;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InformesManager extends Component
{
    public $proceso;
    public $resumen;
    public $descripcion;
    public $noConformidades = [];
    public $informeId = null;
    public $modoEdicion = false;

    public function mount($proceso)
    {
        $this->proceso = $proceso;

        $informeExistente = InformeAuditoria::with('noConformidades')
            ->where('auditoria_proceso_id', $this->proceso->id)
            ->first();

        if ($informeExistente) {

            $this->informeId = $informeExistente->id;
            $this->resumen = $informeExistente->resumen;
            $this->descripcion = $informeExistente->descripcion;
            $this->modoEdicion = true;

            $this->noConformidades = $informeExistente->noConformidades
                ->map(function ($nc) {
                    return [
                        'norma_iso_id' => $nc->norma_iso_id,
                        'descripcion' => $nc->descripcion,
                        'evidencia' => $nc->evidencia,
                        'tipo' => $nc->tipo,
                    ];
                })->toArray();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Agregar / Eliminar dinámico
    |--------------------------------------------------------------------------
    */

    public function agregarNoConformidad()
    {
        $this->noConformidades[] = [
            'norma_iso_id' => '',
            'descripcion' => '',
            'evidencia' => '',
            'tipo' => 'NC',
        ];
    }

    public function eliminarNoConformidad($index)
    {
        unset($this->noConformidades[$index]);
        $this->noConformidades = array_values($this->noConformidades);
    }

    /*
    |--------------------------------------------------------------------------
    | Guardar Informe + No Conformidades
    |--------------------------------------------------------------------------
    */

    public function guardar()
{
    $this->validate([
        'resumen' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'noConformidades.*.norma_iso_id' => 'nullable|exists:normas_iso,id',
        'noConformidades.*.descripcion' => 'nullable|string',
        'noConformidades.*.tipo' => 'nullable|in:NC,O,OM',
    ]);

    DB::transaction(function () {

        if ($this->modoEdicion) {

            $informe = InformeAuditoria::find($this->informeId);

            $informe->update([
                'resumen' => $this->resumen,
                'descripcion' => $this->descripcion,
            ]);

            // Eliminar no conformidades anteriores
            $informe->noConformidades()->delete();

        } else {

            $informe = InformeAuditoria::create([
                'auditoria_proceso_id' => $this->proceso->id,
                'resumen' => $this->resumen,
                'descripcion' => $this->descripcion,
            ]);

            $this->informeId = $informe->id;
            $this->modoEdicion = true;
        }

        foreach ($this->noConformidades as $nc) {
            if (!empty($nc['descripcion'])) {
                $informe->noConformidades()->create([
                    'norma_iso_id' => $nc['norma_iso_id'],
                    'descripcion' => $nc['descripcion'],
                    'evidencia' => $nc['evidencia'],
                    'tipo' => $nc['tipo'],
                ]);
            }
        }
    });
}


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('livewire.admin.auditorias.informes-manager', [

            'requisitos' => AuditoriaNorma::with('norma')
                ->where('auditoria_proceso_id', $this->proceso->id)
                ->whereHas('norma') // evita null
                ->get()
                ->pluck('norma'),

            'informes' => InformeAuditoria::with('noConformidades.norma')
                ->where('auditoria_proceso_id', $this->proceso->id)
                ->latest()
                ->get(),
        ]);
    }
}

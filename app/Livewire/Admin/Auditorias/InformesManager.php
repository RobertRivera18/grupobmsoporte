<?php

namespace App\Livewire\Admin\Auditorias;

use App\Models\InformeAuditoria;
use App\Models\AuditoriaNorma;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PhpOffice\PhpWord\TemplateProcessor;
use AuthorizesRequests;

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
        $this->authorize('view', $this->proceso);

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

    public function limpiarHtml($html)
    {
        if (!$html) {
            return '';
        }

        // Normalizar saltos
        $html = str_replace(["\r\n", "\r"], "\n", $html);

        // Saltos de bloque importantes
        $html = preg_replace('/<\/p>/i', "\n", $html);
        $html = preg_replace('/<\/div>/i', "\n", $html);
        $html = preg_replace('/<br\s*\/?>/i', "\n", $html);

        // Listas
        $html = preg_replace('/<ul[^>]*>/i', "\n", $html);
        $html = preg_replace('/<\/ul>/i', "\n", $html);
        $html = preg_replace('/<li[^>]*>/i', "\n• ", $html);
        $html = preg_replace('/<\/li>/i', "\n", $html);
        // Si una palabra en mayúscula viene pegada después de punto, forzar salto
        $html = preg_replace('/\.([A-ZÁÉÍÓÚÑ])/u', ".\n$1", $html);

        // Eliminar etiquetas restantes
        $html = strip_tags($html);

        // Decodificar entidades
        $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Limpiar múltiples saltos
        $html = preg_replace("/\n{2,}/", "\n", $html);

        $html = trim($html);

        // Convertir a formato Word
        return str_replace("\n", '</w:t><w:br/><w:t>', $html);
    }



    public function descargarInforme()
    {
        if (!$this->informeId) {
            $this->dispatch('error', message: 'Debe guardar el informe antes de generar el documento.');
            return;
        }

        $informe = InformeAuditoria::with([
            'noConformidades.norma',
            'auditoriaProceso.area',
            'auditoriaProceso.auditoria',
        ])->find($this->informeId);

        if (!$informe) {
            $this->dispatch('error', message: 'Informe no encontrado.');
            return;
        }

        $auditoria = $informe->auditoriaProceso->auditoria ?? null;

        $areaNombre = $informe->auditoriaProceso->area->nombre ?? 'N/A';
        $fechaAuditoria = $auditoria?->fecha_inicio
            ? \Carbon\Carbon::parse($auditoria->fecha_inicio)->format('d/m/Y')
            : 'N/A';

        $templatePath = public_path('templates/nueva.docx');

        if (!file_exists($templatePath)) {
            $this->dispatch('error', message: 'Plantilla no encontrada.');
            return;
        }

        $fileName = 'informe_auditoria_' . now()->format('Ymd_His') . '.docx';
        $savePath = public_path('informes/' . $fileName);

        $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        /*
    |--------------------------------------------------------------------------
    | DATOS GENERALES
    |--------------------------------------------------------------------------
    */
        $template->setValue('fecha', now()->format('d/m/Y'));
        $template->setValue('area', htmlspecialchars($areaNombre));
        $template->setValue('fecha_inicio', $fechaAuditoria);

        // 🔥 SOLO descripcion usa Quill
        $template->setValue('resumen', htmlspecialchars($informe->resumen ?? ''));
        $template->setValue('descripcion', $this->limpiarHtml($informe->descripcion));

        /*
    |--------------------------------------------------------------------------
    | HALLAZGOS
    |--------------------------------------------------------------------------
    */
        $hallazgos = $informe->noConformidades;

        if ($hallazgos->count()) {

            $template->cloneRow('no', $hallazgos->count());

            foreach ($hallazgos as $index => $nc) {

                $tipo = match ($nc->tipo) {
                    'NC' => 'No Conformidad',
                    'O'  => 'Observación',
                    'OM' => 'Oportunidad de Mejora',
                    default => 'Hallazgo'
                };

                $i = $index + 1;

                $template->setValue("no#$i", $i);
                $template->setValue("descripcion_nc#$i", htmlspecialchars($nc->descripcion ?? ''));
                $template->setValue("evidencia#$i", htmlspecialchars($nc->evidencia ?? ''));
                $template->setValue("reg_iso#$i", htmlspecialchars($nc->norma->codigo ?? 'N/A'));
                $template->setValue("tipo#$i", $tipo);
            }
        } else {

            $template->setValue('no', '—');
            $template->setValue('descripcion_nc', 'No se registraron hallazgos.');
            $template->setValue('evidencia', '—');
            $template->setValue('reg_iso', '—');
            $template->setValue('tipo', '—');
        }

        $template->saveAs($savePath);

        return response()->download($savePath)->deleteFileAfterSend(true);
    }




    public function render()
    {
        return view('livewire.admin.auditorias.informes-manager', [

            'requisitos' => AuditoriaNorma::with('norma')
                ->where('auditoria_proceso_id', $this->proceso->id)
                ->whereHas('norma')
                ->get()
                ->pluck('norma'),

            'informes' => InformeAuditoria::with('noConformidades.norma')
                ->where('auditoria_proceso_id', $this->proceso->id)
                ->latest()
                ->get(),
        ]);
    }
}

<?php

namespace App\Livewire;

use App\Models\SolicitudDesvinculacion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DesvinculacionBodega extends Component
{
    use AuthorizesRequests;

    public SolicitudDesvinculacion $solicitud;
    public array $descuentos = [];
    public float $total = 0;

    protected function rules(): array
    {
        if (empty($this->descuentos)) {
            return [
                'descuentos' => 'nullable|array',
            ];
        }

        return [
            'descuentos' => 'required|array',
            'descuentos.*.concepto' => 'required|string|max:255',
            'descuentos.*.valor' => 'required|numeric|min:0',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'descuentos.*.concepto' => 'concepto',
            'descuentos.*.valor' => 'valor',
        ];
    }

    public function mount($solicitud)
    {
        if (!($solicitud instanceof SolicitudDesvinculacion)) {
            $this->solicitud = SolicitudDesvinculacion::with(['user', 'cuadrilla', 'descuentos'])
                ->findOrFail($solicitud);
        } else {
            $this->solicitud = $solicitud->load(['user', 'cuadrilla', 'descuentos']);
        }

        if ($this->solicitud->descuentos->count() > 0) {
            foreach ($this->solicitud->descuentos as $item) {
                $this->descuentos[] = [
                    'concepto' => $item->concepto,
                    'valor'    => $item->valor,
                ];
            }
        }

        $this->calcularTotal();
    }

    public function agregarFila()
    {
        $this->authorize('gestionarBodega', $this->solicitud);
        $this->descuentos[] = ['concepto' => '', 'valor' => 0];
    }

    public function eliminarFila($index)
    {
        $this->authorize('gestionarBodega', $this->solicitud);
        unset($this->descuentos[$index]);
        $this->descuentos = array_values($this->descuentos);
        $this->calcularTotal();
    }

    public function calcularTotal()
    {
        $this->total = collect($this->descuentos)->sum(function ($item) {
            return is_numeric($item['valor']) ? (float) $item['valor'] : 0;
        });
    }

    public function guardar()
    {
        $this->authorize('gestionarBodega', $this->solicitud);
        $this->validate();
        DB::transaction(function () {
            $this->solicitud->descuentos()->delete();
            if (!empty($this->descuentos)) {
                foreach ($this->descuentos as $item) {
                    $this->solicitud->descuentos()->create([
                        'concepto' => trim($item['concepto']),
                        'valor'    => (float) $item['valor'],
                    ]);
                }
            }
        });

        $this->solicitud->load('descuentos');
        session()->flash('success', 'Los descuentos de bodega se han actualizado correctamente.');
    }

    public function generarActaLiberacion()
    {
        if (auth()->user()->can('gestionarBodega', $this->solicitud)) {
            $this->guardar();
        }
        $templateName = 'acta_liberacionBodega.docx';
        $templatePath = public_path('templates/' . $templateName);

        if (!file_exists($templatePath)) {
            session()->flash('error', "La plantilla {$templateName} no existe en public/templates.");
            return;
        }

        $this->solicitud->load(['user', 'descuentos']);
        $usuario = $this->solicitud->user;

        if (!$usuario) {
            session()->flash('error', 'La solicitud no tiene un usuario asociado.');
            return;
        }
        $totalFormatted = '$ ' . number_format((float) $this->total, 2);
        $listaDescuentos = $this->solicitud->descuentos
            ->values()
            ->map(fn($item) => [
                'concepto' => mb_strtoupper(trim($item->concepto)),
                'valor'    => '$ ' . number_format((float) $item->valor, 2),
            ])
            ->all();

        if (empty($listaDescuentos)) {
            $listaDescuentos[] = [
                'concepto' => 'SIN DESCUENTOS REGISTRADOS',
                'valor'    => '$ 0.00',
            ];
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        $fechaHoy = now('America/Guayaquil')->locale('es')->isoFormat('D [de] MMMM [de] YYYY');

        $templateProcessor->setValue('fecha', $fechaHoy);
        $templateProcessor->setValue('cedula', $usuario->cedula ?? $usuario->ci ?? 'N/A');
        $templateProcessor->setValue('nombres', mb_strtoupper($usuario->name ?? 'N/A'));
        $templateProcessor->setValue('total_descuentos', $totalFormatted);
        $templateProcessor->cloneRowAndSetValues('concepto', $listaDescuentos);
        $tempFilePath = tempnam(sys_get_temp_dir(), 'acta_') . '.docx';
        $templateProcessor->saveAs($tempFilePath);

        $nombreLimpio = Str::slug($usuario->name ?? 'colaborador', '_');
        $fileName = 'acta_liberacion_' . $nombreLimpio . '_' . now()->format('Ymd_His') . '.docx';
        return response()->streamDownload(function () use ($tempFilePath) {
            readfile($tempFilePath);
            @unlink($tempFilePath);
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }

    public function render()
    {
        return view('livewire.desvinculacion-bodega')
            ->layout('layouts.admin');
    }
}

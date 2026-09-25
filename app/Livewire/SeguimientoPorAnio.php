<?php

namespace App\Livewire;

use App\Models\IndicadorAnio;
use App\Models\Seguimiento;
use Livewire\Component;

class SeguimientoPorAnio extends Component
{
    public IndicadorAnio $anio;

    public $valores = [];
    public $observacion;
    public $periodos = [];

    protected $listeners = [
        'reviewGuardada' => 'actualizarUltimaRevision',
    ];

    public function mount(IndicadorAnio $anio)
    {
        $this->anio = $anio;
        $this->periodos = $this->getPeriodos($anio->indicador->frecuencia_id);

        foreach ($anio->seguimientos as $seg) {
            if (isset($this->periodos[$seg->mes])) {
                $this->valores[$seg->mes] = $seg->valor;
            }
        }

        $this->observacion = $anio->observacion;
    }

    // ============================================================
    // DEVUELVE LOS PERIODOS SEGÚN LA FRECUENCIA DEL INDICADOR
    // ============================================================
    public function getPeriodos($frecuencia)
    {
        return match ($frecuencia) {
            1 => [ // Mensual
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre'
            ],
            2 => [ // Trimestral
                1 => '1er Trimestre',
                2 => '2do Trimestre',
                3 => '3er Trimestre',
                4 => '4to Trimestre',
            ],
            3 => [ // Semestral
                1 => '1er Semestre',
                2 => '2do Semestre',
            ],
            4 => [ // Anual
                1 => 'Resultado Anual',
            ],
            default => [],
        };
    }

    // ============================================================
    // GUARDAR UN PERIODO (MES / TRIMESTRE / SEMESTRE / AÑO)
    // ============================================================
    public function savePeriodo($periodo)
    {
        if (!isset($this->periodos[$periodo])) {
            session()->flash('message', "El periodo $periodo no está permitido para esta frecuencia.");
            return;
        }
        Seguimiento::updateOrCreate(
            [
                'indicador_anio_id' => $this->anio->id,
                'mes' => $periodo
            ],
            [
                'valor' => $this->valores[$periodo] !== "" ? $this->valores[$periodo] : null
            ]
        );

        $this->calcularResultado();
        $this->dispatch('actualizarGrafica');
        $this->dispatch('resultadoActualizado');
        $this->getGraficaData();
        session()->flash('message', "{$this->periodos[$periodo]} guardado correctamente.");
    }

    // ============================================================
    // GUARDAR OBSERVACIÓN
    // ============================================================
    public function saveObservacion()
    {
        $this->anio->update([
            'observacion' => $this->observacion
        ]);

        session()->flash('message', "Observación actualizada.");
    }

    public function actualizarUltimaRevision($anioId)
    {
        if ($this->anio->id === $anioId) {
            $this->anio->update([
                'ultima_revision' => now(),
            ]);
        }
    }

    // ============================================================
    // CALCULA Y ACTUALIZA EL RESULTADO CONSOLIDADO DEL AÑO
    // ============================================================
    public function calcularResultado()
    {
        $valores = array_filter($this->valores, fn($v) => $v !== null && $v !== "");

        if (count($valores) === 0) {
            $this->anio->update(['resultado_obtenido' => null]);
            return;
        }

        // Calcula el promedio de los periodos ingresados hasta el momento
        $promedio = array_sum($valores) / count($valores);
        $resultado = round($promedio, 2);

        $this->anio->update([
            'resultado_obtenido' => $resultado
        ]);

        // Refresca la instancia del modelo local
        $this->anio->refresh();
    }

    // ============================================================
    // EVALÚA SI EL INDICADOR ESTÁ BIEN O MAL SEGÚN SU SENTIDO
    // ============================================================
    public function getEstadoEvaluacionProperty()
    {
        if (is_null($this->anio->resultado_obtenido) || is_null($this->anio->meta)) {
            return [
                'cumple' => null,
                'mensaje' => 'Sin datos suficientes',
                'color' => 'bg-gray-100 text-gray-600',
                'icono' => 'fas fa-minus'
            ];
        }

        $sentido = $this->anio->indicador->sentido ?? 'ascendente';
        $resultado = (float) $this->anio->resultado_obtenido;
        $meta = (float) $this->anio->meta;

        $cumple = match ($sentido) {
            'descendente' => $resultado <= $meta,
            'mantenimiento' => $resultado == $meta,
            default => $resultado >= $meta, // 'ascendente'
        };

        if ($cumple) {
            return [
                'cumple' => true,
                'mensaje' => 'Meta cumplida',
                'color' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'icono' => 'fas fa-check-circle text-emerald-600'
            ];
        }

        return [
            'cumple' => false,
            'mensaje' => 'Fuera de meta',
            'color' => 'bg-rose-100 text-rose-800 border-rose-300',
            'icono' => 'fas fa-exclamation-triangle text-rose-600'
        ];
    }

    public function getGraficaData()
    {
        return [
            'labels'   => array_values($this->periodos),
            'valores'  => array_map(fn($p) => $this->valores[$p] ?? null, array_keys($this->periodos)),
            'metaBase' => $this->anio->meta ?? 0,
        ];
    }

    public function render()
    {
        return view('livewire.seguimiento-por-anio', [
            'grafica' => $this->getGraficaData(),
            'estado'  => $this->estadoEvaluacion,
        ]);
    }
}
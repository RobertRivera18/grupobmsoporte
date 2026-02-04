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

        // ---------------------------------------------------------
        // 1️⃣ OBTENER PERIODOS SEGÚN FRECUENCIA
        // ---------------------------------------------------------
        $this->periodos = $this->getPeriodos($anio->indicador->frecuencia_id);

        // ---------------------------------------------------------
        // 2️⃣ CARGAR VALORES YA EXISTENTES
        // ---------------------------------------------------------
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
                'valor' => $this->valores[$periodo] ?? null
            ]
        );

        $this->calcularResultado();
        $this->dispatch('actualizarGrafica');
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



    public function calcularResultado()
    {
        // Obtener todos los valores registrados
        $valores = array_filter($this->valores, fn($v) => $v !== null && $v !== "");

        if (count($valores) === 0) {
            return null; // Nada registrado
        }

        // Promedio simple
        $promedio = array_sum($valores) / count($valores);

        // Actualizar en base de datos
        $this->anio->update([
            'resultado_obtenido' => round($promedio, 2)
        ]);
    }

    //Crear grafica estadistica en base a los valores ingresados 
    public function getGraficaData()
    {
        return [
            'labels'    => array_values($this->periodos),       // Nombres de periodos
            'valores'   => array_map(fn($p) => $this->valores[$p] ?? null, array_keys($this->periodos)),
            'metaBase'  => $this->anio->meta ?? 0,              // Meta del año
            // 'frecuencia' => $this->anio->indicador->frecuencia->nombre
        ];
    }

    public function render()
    {
        return view('livewire.seguimiento-por-anio', [
            'grafica' => $this->getGraficaData()
        ]);
    }
}

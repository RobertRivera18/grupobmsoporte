<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Cotizacion;
use App\Models\Entorno;
use App\Models\EquipoElectrico;
use App\Models\Generador;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;


class Cotizador extends Component
{
    // ----- Navegación -----
    public int $step = 1;

    // ----- Paso 1: Entorno -----
    public ?int $entorno_id = null;

    // ----- Paso 2: Equipos -----
    // [equipo_id => ['seleccionado' => bool, 'cantidad' => int]]
    public array $equipos = [];

    public string $busquedaEquipo = '';

    // ----- Paso 3: Prioridad (opcional) -----
    // [equipo_id => 'critico' | 'intermitente' | null]
    public array $prioridades = [];

    // ----- Paso 4: Resultado -----
    public array $filtroCombustible = [
        'gasolina' => false,
        'diesel' => false,
        'gas' => false,
    ];

    // ----- Modal de cotización -----
    public bool $mostrarModalCotizacion = false;

    public string $nombre_cliente = '';

    public string $telefono = '';

    public string $email = '';

    public bool $cotizacionEnviada = false;

    protected float $margenSeguridad = 0.25; // 25%

    protected float $factorPotencia = 0.8; // kW = kVA * 0.8

    public function mount(): void
    {
        // Preselecciona el primer entorno, igual que en el mockup (Residencial marcado por defecto)
        $primerEntorno = Entorno::orderBy('orden')->first();

        if ($primerEntorno) {
            $this->seleccionarEntorno($primerEntorno->id);
        }
    }

    // =========================================================
    //  PASO 1 · ENTORNO
    // =========================================================

    #[Computed]
    public function entornos(): Collection
    {
        return Entorno::orderBy('orden')->get();
    }

    public function seleccionarEntorno(int $entornoId): void
    {
        $this->entorno_id = $entornoId;

        $entorno = Entorno::with('equipos')->find($entornoId);

        if (! $entorno) {
            return;
        }

        // Carga el catálogo por defecto de ese entorno (cantidades y selección)
        $this->equipos = [];
        foreach ($entorno->equipos as $equipo) {
            $this->equipos[$equipo->id] = [
                'seleccionado' => (bool) $equipo->pivot->seleccionado_defecto,
                'cantidad' => (int) $equipo->pivot->cantidad_defecto,
            ];
        }

        $this->prioridades = [];
        $this->resetResultado();
    }

    // =========================================================
    //  PASO 2 · EQUIPOS
    // =========================================================

    #[Computed]
    public function sugerenciasBusqueda(): Collection
    {
        if (strlen($this->busquedaEquipo) < 2) {
            return collect();
        }

        $idsExistentes = array_keys($this->equipos);

        return EquipoElectrico::where('nombre', 'like', '%'.$this->busquedaEquipo.'%')
            ->whereNotIn('id', $idsExistentes)
            ->limit(6)
            ->get();
    }

    public function agregarEquipo(int $equipoId): void
    {
        $this->equipos[$equipoId] = [
            'seleccionado' => true,
            'cantidad' => 1,
        ];

        $this->busquedaEquipo = '';
    }

    public function toggleEquipo(int $equipoId): void
    {
        if (! isset($this->equipos[$equipoId])) {
            return;
        }

        $this->equipos[$equipoId]['seleccionado'] = ! $this->equipos[$equipoId]['seleccionado'];

        // Si se activa y la cantidad estaba en cero, arranca en 1
        if ($this->equipos[$equipoId]['seleccionado'] && $this->equipos[$equipoId]['cantidad'] < 1) {
            $this->equipos[$equipoId]['cantidad'] = 1;
        }
    }

    public function actualizarCantidad(int $equipoId, int $cantidad): void
    {
        $cantidad = max(0, $cantidad);

        $this->equipos[$equipoId]['cantidad'] = $cantidad;
        $this->equipos[$equipoId]['seleccionado'] = $cantidad > 0;
    }

    public function quitarEquipo(int $equipoId): void
    {
        unset($this->equipos[$equipoId], $this->prioridades[$equipoId]);
    }

    /**
     * Equipos realmente activos en la cotización: marcados y con cantidad > 0.
     */
    #[Computed]
    public function equiposActivos(): Collection
    {
        $ids = collect($this->equipos)
            ->filter(fn ($e) => $e['seleccionado'] && $e['cantidad'] > 0)
            ->keys();

        return EquipoElectrico::whereIn('id', $ids)->get()->map(function (EquipoElectrico $equipo) {
            $equipo->cantidad_seleccionada = $this->equipos[$equipo->id]['cantidad'];

            return $equipo;
        });
    }

    #[Computed]
    public function catalogoCompleto(): Collection
    {
        return EquipoElectrico::whereIn('id', array_keys($this->equipos))->get()->keyBy('id');
    }

    // =========================================================
    //  PASO 3 · PRIORIDAD
    // =========================================================

    public function marcarPrioridad(int $equipoId, string $tipo): void
    {
        // tipo: 'critico' | 'intermitente'
        if (($this->prioridades[$equipoId] ?? null) === $tipo) {
            // clic de nuevo sobre lo mismo -> lo desmarca
            unset($this->prioridades[$equipoId]);

            return;
        }

        $this->prioridades[$equipoId] = $tipo;
    }

    #[Computed]
    public function equiposCriticos(): Collection
    {
        return $this->equiposActivos()->filter(
            fn (EquipoElectrico $e) => ($this->prioridades[$e->id] ?? null) === 'critico'
        );
    }

    #[Computed]
    public function equiposIntermitentes(): Collection
    {
        return $this->equiposActivos()->filter(
            fn (EquipoElectrico $e) => ($this->prioridades[$e->id] ?? null) === 'intermitente'
        );
    }

    // =========================================================
    //  PASO 4 · CÁLCULO Y RECOMENDACIÓN
    // =========================================================

    public function calcularCapacidad(): void
    {
        $this->step = 4;
    }

    /**
     * Motor de cálculo de capacidad recomendada.
     *
     * 1. Suma la potencia nominal de todo lo seleccionado (carga en régimen).
     * 2. Añade el pico de arranque adicional del motor más exigente entre los
     *    equipos marcados (un generador solo debe cubrir el arranque más alto
     *    que puede coincidir, no la suma de todos).
     * 3. Aplica el margen de seguridad (25%).
     * 4. Convierte kW -> kVA usando el factor de potencia (0.8).
     */
    #[Computed]
    public function resultado(): array
    {
        $activos = $this->equiposActivos();

        $potenciaNominalW = $activos->sum(
            fn (EquipoElectrico $e) => $e->potencia_nominal_w * $e->cantidad_seleccionada
        );

        $mayorSurgeW = $activos
            ->filter(fn (EquipoElectrico $e) => $e->es_motor)
            ->map(fn (EquipoElectrico $e) => $e->surge_extra_watts)
            ->max() ?? 0;

        $potenciaRequeridaW = $potenciaNominalW + $mayorSurgeW;
        $margenW = $potenciaRequeridaW * $this->margenSeguridad;
        $potenciaTotalW = $potenciaRequeridaW + $margenW;

        $capacidadKw = round($potenciaTotalW / 1000, 2);
        $capacidadKva = round($capacidadKw / $this->factorPotencia, 2);

        return [
            'potencia_nominal_w' => $potenciaNominalW,
            'mayor_surge_w' => $mayorSurgeW,
            'margen_w' => round($margenW),
            'potencia_total_w' => round($potenciaTotalW),
            'capacidad_kw' => $capacidadKw,
            'capacidad_kva' => $capacidadKva,
        ];
    }

    #[Computed]
    public function generadoresRecomendados(): Collection
    {
        $combustiblesActivos = collect($this->filtroCombustible)
            ->filter()
            ->keys()
            ->toArray();

        return Generador::activos()
            ->combustibleEn($combustiblesActivos)
            ->conCapacidadMinima($this->resultado()['capacidad_kva'])
            ->orderBy('capacidad_kva')
            ->limit(4)
            ->get();
    }

    protected function resetResultado(): void
    {
        unset($this->resultado, $this->generadoresRecomendados);
    }

    // =========================================================
    //  NAVEGACIÓN ENTRE PASOS
    // =========================================================

    public function siguientePaso(): void
    {
        if ($this->step === 1 && ! $this->entorno_id) {
            $this->addError('entorno', 'Selecciona un entorno para continuar.');

            return;
        }

        if ($this->step === 2 && $this->equiposActivos()->isEmpty()) {
            $this->addError('equipos', 'Selecciona al menos un equipo con cantidad mayor a cero.');

            return;
        }

        $this->step = min(4, $this->step + 1);
    }

    public function pasoAnterior(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function irAlPaso(int $paso): void
    {
        // Solo permite saltar hacia atrás libremente, o hacia adelante si ya hay entorno + equipos
        if ($paso <= $this->step || ($this->entorno_id && $this->equiposActivos()->isNotEmpty())) {
            $this->step = $paso;
        }
    }

    // =========================================================
    //  COTIZACIÓN / CONTACTO
    // =========================================================

    public function abrirModalCotizacion(): void
    {
        $this->mostrarModalCotizacion = true;
    }

    public function cerrarModalCotizacion(): void
    {
        $this->mostrarModalCotizacion = false;
    }

    public function enviarCotizacion(?int $generadorId = null): void
    {
        $this->validate([
            'nombre_cliente' => 'required|string|max:120',
            'telefono' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
        ]);

        $resultado = $this->resultado();

        Cotizacion::create([
            'entorno_id' => $this->entorno_id,
            'generador_id' => $generadorId,
            'equipos_json' => $this->equiposActivos()->map(fn (EquipoElectrico $e) => [
                'id' => $e->id,
                'nombre' => $e->nombre,
                'cantidad' => $e->cantidad_seleccionada,
                'prioridad' => $this->prioridades[$e->id] ?? null,
            ])->values()->toArray(),
            'potencia_total_w' => $resultado['potencia_total_w'],
            'capacidad_recomendada_kva' => $resultado['capacidad_kva'],
            'capacidad_recomendada_kw' => $resultado['capacidad_kw'],
            'nombre_cliente' => $this->nombre_cliente,
            'telefono' => $this->telefono,
            'email' => $this->email,
        ]);

        $this->cotizacionEnviada = true;
        $this->mostrarModalCotizacion = false;
    }

    public function render()
    {
        return view('livewire.cotizador')->layout('layouts.app');
    }
}

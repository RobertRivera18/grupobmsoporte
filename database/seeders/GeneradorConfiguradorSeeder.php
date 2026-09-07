<?php

namespace Database\Seeders;

use App\Models\Entorno;
use App\Models\EquipoElectrico;
use App\Models\Generador;
use Illuminate\Database\Seeder;

class GeneradorConfiguradorSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- ENTORNOS ----------
        $entornos = [
            ['nombre' => 'Residencial', 'slug' => 'residencial', 'icono' => 'home', 'orden' => 1],
            ['nombre' => 'Comercial', 'slug' => 'comercial', 'icono' => 'building', 'orden' => 2],
            ['nombre' => 'Industrial', 'slug' => 'industrial', 'icono' => 'factory', 'orden' => 3],
            ['nombre' => 'Eventos', 'slug' => 'eventos', 'icono' => 'sparkles', 'orden' => 4],
            ['nombre' => 'Campamento', 'slug' => 'campamento', 'icono' => 'tent', 'orden' => 5],
        ];

        foreach ($entornos as $data) {
            Entorno::updateOrCreate(['slug' => $data['slug']], $data);
        }

        // ---------- CATÁLOGO DE EQUIPOS ----------
        // potencia_arranque_w solo aplica a equipos con motor (es_motor = true)
        $equipos = [
            ['nombre' => 'Refrigeradora', 'icono' => '🧊', 'potencia_nominal_w' => 150, 'potencia_arranque_w' => 450, 'es_motor' => true],
            ['nombre' => 'Aire Acond. 12k BTU', 'icono' => '❄️', 'potencia_nominal_w' => 1200, 'potencia_arranque_w' => 3600, 'es_motor' => true],
            ['nombre' => 'Luces LED', 'icono' => '💡', 'potencia_nominal_w' => 10, 'potencia_arranque_w' => null, 'es_motor' => false],
            ['nombre' => 'TV', 'icono' => '📺', 'potencia_nominal_w' => 120, 'potencia_arranque_w' => null, 'es_motor' => false],
            ['nombre' => 'Lavadora', 'icono' => '🧺', 'potencia_nominal_w' => 500, 'potencia_arranque_w' => 1500, 'es_motor' => true],
            ['nombre' => 'Bomba de Agua', 'icono' => '🚰', 'potencia_nominal_w' => 750, 'potencia_arranque_w' => 2200, 'es_motor' => true],
            ['nombre' => 'Microondas', 'icono' => '🍽️', 'potencia_nominal_w' => 1100, 'potencia_arranque_w' => null, 'es_motor' => false],
            ['nombre' => 'Computadora', 'icono' => '💻', 'potencia_nominal_w' => 300, 'potencia_arranque_w' => null, 'es_motor' => false],
            ['nombre' => 'Cafetera', 'icono' => '☕', 'potencia_nominal_w' => 900, 'potencia_arranque_w' => null, 'es_motor' => false],
            ['nombre' => 'Congelador', 'icono' => '🧊', 'potencia_nominal_w' => 200, 'potencia_arranque_w' => 600, 'es_motor' => true],
            ['nombre' => 'Herramienta Eléctrica', 'icono' => '🛠️', 'potencia_nominal_w' => 1000, 'potencia_arranque_w' => 2500, 'es_motor' => true],
            ['nombre' => 'Cocina de Inducción', 'icono' => '🍳', 'potencia_nominal_w' => 1800, 'potencia_arranque_w' => null, 'es_motor' => false],
            ['nombre' => 'Router / Módem', 'icono' => '📶', 'potencia_nominal_w' => 15, 'potencia_arranque_w' => null, 'es_motor' => false],
            ['nombre' => 'Ventilador', 'icono' => '🌀', 'potencia_nominal_w' => 75, 'potencia_arranque_w' => 150, 'es_motor' => true],
            ['nombre' => 'Cargador de Celular', 'icono' => '🔌', 'potencia_nominal_w' => 10, 'potencia_arranque_w' => null, 'es_motor' => false],
        ];

        $equiposModel = [];
        foreach ($equipos as $data) {
            $equiposModel[$data['nombre']] = EquipoElectrico::updateOrCreate(['nombre' => $data['nombre']], $data);
        }

        // ---------- CATÁLOGO DEFAULT POR ENTORNO ----------
        // [nombre_equipo => [cantidad_defecto, seleccionado_defecto, orden]]
        $catalogoPorEntorno = [
            'residencial' => [
                'Refrigeradora' => [1, true, 1],
                'Aire Acond. 12k BTU' => [0, false, 2],
                'Luces LED' => [10, true, 3],
                'TV' => [1, false, 4],
                'Lavadora' => [0, false, 5],
                'Microondas' => [0, false, 6],
                'Computadora' => [0, false, 7],
                'Router / Módem' => [1, true, 8],
            ],
            'comercial' => [
                'Luces LED' => [20, true, 1],
                'Aire Acond. 12k BTU' => [2, true, 2],
                'Computadora' => [4, true, 3],
                'Refrigeradora' => [1, true, 4],
                'Cafetera' => [1, false, 5],
                'Router / Módem' => [2, true, 6],
            ],
            'industrial' => [
                'Herramienta Eléctrica' => [3, true, 1],
                'Bomba de Agua' => [1, true, 2],
                'Luces LED' => [30, true, 3],
                'Ventilador' => [4, false, 4],
            ],
            'eventos' => [
                'Luces LED' => [40, true, 1],
                'Ventilador' => [4, false, 2],
                'Cocina de Inducción' => [1, false, 3],
                'Cargador de Celular' => [10, false, 4],
            ],
            'campamento' => [
                'Luces LED' => [6, true, 1],
                'Congelador' => [1, false, 2],
                'Bomba de Agua' => [1, true, 3],
                'Cargador de Celular' => [4, true, 4],
            ],
        ];

        foreach ($catalogoPorEntorno as $slug => $items) {
            $entorno = Entorno::where('slug', $slug)->first();

            $sync = [];
            foreach ($items as $nombreEquipo => [$cantidad, $seleccionado, $orden]) {
                $sync[$equiposModel[$nombreEquipo]->id] = [
                    'cantidad_defecto' => $cantidad,
                    'seleccionado_defecto' => $seleccionado,
                    'orden' => $orden,
                ];
            }

            $entorno->equipos()->sync($sync);
        }

        // ---------- GENERADORES ----------
        $generadores = [
            ['nombre' => 'Generador 5kVA - Gasolina', 'capacidad_kva' => 5, 'capacidad_kw' => 4, 'combustible' => 'gasolina', 'precio' => 650],
            ['nombre' => 'Generador 8kVA - Gasolina', 'capacidad_kva' => 8, 'capacidad_kw' => 6.4, 'combustible' => 'gasolina', 'precio' => 950],
            ['nombre' => 'Generador 10kVA - Gasolina', 'capacidad_kva' => 10, 'capacidad_kw' => 8, 'combustible' => 'gasolina', 'precio' => 1250],
            ['nombre' => 'Generador 10kVA - Diésel', 'capacidad_kva' => 10, 'capacidad_kw' => 8, 'combustible' => 'diesel', 'precio' => 1650],
            ['nombre' => 'Generador 15kVA - Diésel', 'capacidad_kva' => 15, 'capacidad_kw' => 12, 'combustible' => 'diesel', 'precio' => 2450],
            ['nombre' => 'Generador 20kVA - Diésel', 'capacidad_kva' => 20, 'capacidad_kw' => 16, 'combustible' => 'diesel', 'precio' => 3200],
            ['nombre' => 'Generador 12kVA - Gas', 'capacidad_kva' => 12, 'capacidad_kw' => 9.6, 'combustible' => 'gas', 'precio' => 2100],
            ['nombre' => 'Generador 30kVA - Diésel', 'capacidad_kva' => 30, 'capacidad_kw' => 24, 'combustible' => 'diesel', 'precio' => 4800],
        ];

        foreach ($generadores as $data) {
            Generador::updateOrCreate(['nombre' => $data['nombre']], $data);
        }
    }
}

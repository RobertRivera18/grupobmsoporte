<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoActividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Instalaciones'],
            ['nombre' => 'Traslados'],
            ['nombre' => 'Mantenimientos'],
            ['nombre' => 'garantias'],
            ['nombre' => 'cambio de equipos'],
            ['nombre' => 'reubicacion'],
            ['nombre' => 'reposicion de equipos'],
        ];

        foreach ($tipos as $tipo) {
            DB::table('tipo_actividads')->updateOrInsert(
                ['nombre' => $tipo['nombre']], 
                $tipo
            );
        }
    }
}

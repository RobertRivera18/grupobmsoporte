<?php

namespace Database\Seeders;

use App\Models\TipoEquipo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoEquiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposequipos = [
            'Monitores',
            'CPU',
            'Tablets',
            'Chips',
            'Laptops',
            'Adaptadores de Red',
            'Switch',
            'Servidores',
            'Aires Acondicionados',
            'Medidores de Campo',
            'Televisores'
            
        ];
        foreach ($tiposequipos as $tipo) {
             TipoEquipo::firstOrCreate(['nombre' => $tipo]);
        }
    }
}

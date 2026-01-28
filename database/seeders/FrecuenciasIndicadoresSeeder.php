<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FrecuenciasIndicadoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('frecuencias_indicadores')->insert([
        ['nombre' => 'Mensual'],
        ['nombre' => 'Trimestral'],
        ['nombre' => 'Semestral'],
        ['nombre' => 'Anual'],
    ]);
    }
}

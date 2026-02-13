<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NormasIsoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('normas_iso')->insert([
            ['codigo' => '1', 'descripcion' => 'Alcance'],
            ['codigo' => '2', 'descripcion' => 'Referencias normativas'],
            ['codigo' => '3', 'descripcion' => 'Términos y definiciones'],

            ['codigo' => '4', 'descripcion' => 'Contexto de la organización'],
            ['codigo' => '4.1', 'descripcion' => 'Comprensión de la organización y su contexto'],
            ['codigo' => '4.2', 'descripcion' => 'Comprensión de las necesidades y expectativas de las partes interesadas'],
            ['codigo' => '4.3', 'descripcion' => 'Determinación del alcance del sistema de gestión de la calidad'],
            ['codigo' => '4.4', 'descripcion' => 'Sistema de gestión de la calidad y sus procesos'],

            ['codigo' => '5', 'descripcion' => 'Liderazgo'],
            ['codigo' => '5.1', 'descripcion' => 'Liderazgo y compromiso'],
            ['codigo' => '5.2', 'descripcion' => 'Política'],
            ['codigo' => '5.3', 'descripcion' => 'Roles, responsabilidades y autoridades en la organización'],

            ['codigo' => '6', 'descripcion' => 'Planificación'],
            ['codigo' => '6.1', 'descripcion' => 'Acciones para abordar riesgos y oportunidades'],
            ['codigo' => '6.2', 'descripcion' => 'Objetivos de la calidad y planificación para lograrlos'],
            ['codigo' => '6.3', 'descripcion' => 'Planificación de los cambios'],

            ['codigo' => '7', 'descripcion' => 'Apoyo'],
            ['codigo' => '7.1', 'descripcion' => 'Recursos'],
            ['codigo' => '7.2', 'descripcion' => 'Competencia'],
            ['codigo' => '7.3', 'descripcion' => 'Toma de conciencia'],
            ['codigo' => '7.4', 'descripcion' => 'Comunicación'],
            ['codigo' => '7.5', 'descripcion' => 'Información documentada'],

            ['codigo' => '8', 'descripcion' => 'Operación'],
            ['codigo' => '8.1', 'descripcion' => 'Planificación y control operacional'],
            ['codigo' => '8.2', 'descripcion' => 'Requisitos para los productos y servicios'],
            ['codigo' => '8.3', 'descripcion' => 'Diseño y desarrollo de los productos y servicios'],
            ['codigo' => '8.4', 'descripcion' => 'Control de los procesos, productos y servicios suministrados externamente'],
            ['codigo' => '8.5', 'descripcion' => 'Producción y provisión del servicio'],
            ['codigo' => '8.6', 'descripcion' => 'Liberación de los productos y servicios'],
            ['codigo' => '8.7', 'descripcion' => 'Control de las salidas no conformes'],

            ['codigo' => '9', 'descripcion' => 'Evaluación del desempeño'],
            ['codigo' => '9.1', 'descripcion' => 'Seguimiento, medición, análisis y evaluación'],
            ['codigo' => '9.2', 'descripcion' => 'Auditoría interna'],
            ['codigo' => '9.3', 'descripcion' => 'Revisión por la dirección'],

            ['codigo' => '10', 'descripcion' => 'Mejora'],
            ['codigo' => '10.1', 'descripcion' => 'Generalidades'],
            ['codigo' => '10.2', 'descripcion' => 'No conformidad y acción correctiva'],
            ['codigo' => '10.3', 'descripcion' => 'Mejora continua'],
        ]);
    }
}

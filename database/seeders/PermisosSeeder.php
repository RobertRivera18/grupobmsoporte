<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [
            'Acceso al Dashboard',
            'Gestion de Usarios',
            'Gestion de Categorias',
            'Gestion de Articulos',
            'Gestion de roles',
            'Gestion de permisos',
            'Gestion de equipos',
            'Gestion de cuadrillas',
            'Gestion de Equipos-Usuarios',
            'Gestion de Equipos-Cuadrillas',
            'Gestion de Credenciales',
            'Gestion de Tickets',
            'Gestion de Tipos de Equipos',
        ];
        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }
    }
}

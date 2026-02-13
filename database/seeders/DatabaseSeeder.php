<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear otros datos de prueba
        User::factory(20)->create();
        Category::factory(5)->create();
        Post::factory(100)->create();
        

        // Crear el rol Admin si no existe
        Role::firstOrCreate(['name' => 'Admin']);

        // Crear usuario admin y asignarle el rol
        $admin = User::factory()->create([
            'name' => 'Robert Rivera',
            'email' => 'rxrc1819@gmail.com',
            'password' => bcrypt('1234567'),
        ]);

        $admin->assignRole('Admin');
        $this->call(TagSeeder::class);
        $this->call(PermisosSeeder::class);
        $this->call(TipoEquiposSeeder::class);
        $this->call(FrecuenciasIndicadoresSeeder::class);
        $this->call(NormasIsoSeeder::class);

    }
}

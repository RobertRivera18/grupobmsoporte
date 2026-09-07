<?php

use App\Http\Controllers\ConsultasController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\IncidenteController;
use App\Http\Controllers\IncidenteVehiculoController;
use App\Livewire\Cotizador;
use App\Livewire\EquiposTable;
use App\Livewire\InventarioReporte;
use Illuminate\Support\Facades\Artisan;

Route::get('/', HomeController::class)->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('contacts', [ContactController::class, 'index'])->name('contact.index');
Route::post('contacts', [ContactController::class, 'store'])->name('contact.store');
Route::get('/datatable/users', [DatatableController::class, 'user'])->name('datatable.users');
Route::get('equipoInfo/{user}', [EquipoController::class, 'show'])->name('equipo.info');
Route::get('/equipos/exportar', [EquiposTable::class, 'exportarExcel'])->name('equipos.exportar');

//Solo para produccion
Route::get("/generate-link-simbolik", function () {
    Artisan::call("storage:link");
    return "storage-link-exceute";
});
Route::get('/incidentes', [IncidenteController::class, 'index']);
Route::get('/consultas', [ConsultasController::class, 'index']);
Route::post('incidentesvehiculos/buscar-por-placa', [IncidenteVehiculoController::class, 'buscarPorPlaca']);
Route::post('incidentesvehiculos/subir-fotos', [IncidenteVehiculoController::class, 'subirFotos']);
Route::get('incidentesvehiculos/listar-choferes', [IncidenteVehiculoController::class, 'listarChoferes']);

Route::resource('incidentesvehiculos', IncidenteVehiculoController::class);

Route::get('tecnicos', InventarioReporte::class)->name('tecnicos.inventario');
Route::get('cotizador',Cotizador::class);


<?php

use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\AuditoriaController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CredencialesController;
use App\Http\Controllers\Admin\CuadrillaController;
use App\Http\Controllers\Admin\DesvinculacionController;
use App\Http\Controllers\Admin\DevolucionIndumentariaController;
use App\Http\Controllers\Admin\EntregaIndumentariaController;
use App\Http\Controllers\Admin\EquiposController;
use App\Http\Controllers\Admin\GestionInventarioController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\IncidenteController;
use App\Http\Controllers\Admin\IndumentariaController;
use App\Http\Controllers\Admin\IngresoIndumentariaController;
use App\Http\Controllers\Admin\KardexIndumentariaController;
use App\Http\Controllers\Admin\MantenimientosVehiculosController;
use App\Http\Controllers\Admin\RevisionesVehiculares;
use App\Http\Controllers\Admin\SalidaEquiposController;
use App\Http\Controllers\Admin\TipoEquipoController;
use App\Http\Controllers\Admin\TrasladosController;
use App\Http\Controllers\Admin\VehiculoController;
use App\Http\Controllers\TipoContratoController;
use App\Livewire\Admin\Capacitacion\Courses\Create;
use App\Livewire\Admin\Capacitacion\Courses\Edit;
use App\Livewire\Admin\Capacitacion\Courses\Index;
use App\Livewire\Admin\Capacitacion\Courses\QuizBuilder;
use App\Livewire\Admin\Capacitacion\Courses\Show;
use App\Livewire\Admin\Enrollments\Index as EnrollmentsIndex;
use App\Livewire\ExecutiveDashboard;

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('admin.dashboard');
})->middleware(['can:Acceso al Dashboard'])
    ->name('admin.dashboard');

Route::resource('/categories', CategoryController::class)
    ->middleware(['can:Gestion de Categorias'])
    ->except('show')
    ->names('admin.categories');

Route::resource('/posts', PostController::class)
    ->middleware(['can:Gestion de Articulos'])
    ->names('admin.posts');

Route::resource('/roles', RoleController::class)
    ->middleware(['can:Gestion de roles'])
    ->names('admin.roles');

Route::resource('/permissions', PermissionController::class)
    ->middleware(['can:Gestion de permisos'])
    ->names('admin.permissions');

Route::resource('/users', UserController::class)
    ->middleware(['can:Gestion de Usuarios'])
    ->names('admin.users');

Route::get('/equipos/asignacion', [EquiposController::class, 'asignacionEquipos'])
    ->middleware(['can:Gestion de Equipos-Usuarios'])
    ->name('admin.asignacion');

Route::get('/equipos/historialEquipo/{equipo}', [EquiposController::class, 'historial'])
    ->name('admin.historial');

Route::resource('/equipos', EquiposController::class)
    ->middleware(['can:Gestion de equipos'])
    ->names('admin.equipos');

Route::resource('/cuadrillas', CuadrillaController::class)
    ->middleware(['can:Gestion de cuadrillas'])
    ->names('admin.cuadrillas');

Route::get('/equipostecnicos', [EquiposController::class, 'equipostecnicos'])
    ->middleware(['can:Gestion de Equipos-Cuadrillas'])
    ->name('admin.tecnicos');

Route::get('/credenciales', [CredencialesController::class, 'index'])
    ->middleware(['can:Gestion de Credenciales'])
    ->name('admin.credenciales.index');


Route::resource('/tickets', TicketController::class)
    ->middleware(['can:Gestion de Tickets'])
    ->names('admin.tickets');


Route::patch('/admin/tickets/{ticket}/edit/estado', [TicketController::class, 'cambiarEstado'])
    ->middleware(['can:Gestion de Tickets'])
    ->name('admin.tickets.estado');

Route::resource('/tipoequipos', TipoEquipoController::class)
    ->middleware(['can:Gestion de Tipos de Equipos'])
    ->names('admin.tipoequipos');

Route::resource('/reportes', ReportController::class)->names('admin.reportes')
    ->middleware(['can:Gestion de Tipos de Equipos']);

Route::resource('/tipocontratos', TipoContratoController::class)
    ->middleware(['can:Gestion de Tipos de Equipos'])
    ->names('admin.tipocontratos');


Route::get(
    '/areas/{area}/indicadores/{indicador}',
    [AreaController::class, 'showIndicador']
)->name('admin.areas.indicadores.show');

Route::resource('/areas', AreaController::class)
    ->names('admin.areas');
Route::resource('/incidentes', IncidenteController::class)
    ->names('admin.incidentes');


Route::resource('/indumentarias', IndumentariaController::class)
    ->except('show')
    ->names('admin.indumentarias');


//Ruta para ingresos 
Route::prefix('indumentarias')->group(function () {
    Route::get('/ingresos', [IngresoIndumentariaController::class, 'index'])
        ->name('admin.indumentarias.ingresos.index');
    Route::post('/ingresos', [IngresoIndumentariaController::class, 'store'])
        ->name('admin.indumentarias.ingresos.store');
});


//Ruta para Entregas 
Route::prefix('indumentarias')->group(function () {
    Route::get('/entregas', [EntregaIndumentariaController::class, 'index'])
        ->middleware(['can:Gestion de Articulos'])
        ->name('admin.indumentarias.entregas.index');

    Route::post('/entregas', [EntregaIndumentariaController::class, 'store'])
        ->middleware(['can:Gestion de Articulos'])
        ->name('admin.indumentarias.entregas.store');
});

//Ruta para Devoluciones 

Route::prefix('indumentarias')->group(function () {
    Route::get('/devoluciones', [DevolucionIndumentariaController::class, 'index'])
        ->middleware(['can:Gestion de Articulos'])
        ->name('admin.indumentarias.devoluciones.index');

    Route::post('/devoluciones', [DevolucionIndumentariaController::class, 'store'])
        ->middleware(['can:Gestion de Articulos'])
        ->name('admin.indumentarias.devoluciones.store');
});


//Ruta para traslados 
Route::prefix('indumentarias')->group(function () {
    Route::get('/traslados', [TrasladosController::class, 'index'])
        ->middleware(['can:Gestion de Articulos'])
        ->name('admin.indumentarias.traslados.index');

    Route::post('/traslados', [TrasladosController::class, 'store'])
        ->middleware(['can:Gestion de Articulos'])
        ->name('admin.indumentarias.traslados.store');
});

// Ruta para Kárdex
Route::prefix('indumentarias')->group(function () {
    Route::get('/kardex', [KardexIndumentariaController::class, 'index'])
        ->middleware(['can:Gestion de Articulos'])
        ->name('admin.indumentarias.kardex.index');
});


Route::get(
    '/indumentarias/devoluciones/empleado/{user}',
    [EntregaIndumentariaController::class, 'entregasPorEmpleado']
)->name('admin.indumentarias.devoluciones.entregas.empleado');


Route::get('calendar', [CalendarController::class, 'index'])
    ->name('admin.calendar.index');


//Carga Informes de Auditoria
Route::get(
    '/auditorias/{auditoria}/procesos/{proceso}',
    [AuditoriaController::class, 'detalle']
)->name('admin.auditorias.procesos.detalle')
    ->middleware(['can:Auditorias']);

//Ruta Resoruce de Auditorias
Route::resource('/auditorias', AuditoriaController::class)
    ->names('admin.auditorias')
    ->middleware(['can:Auditorias']);

Route::patch('auditorias/{auditoria}/estado', [AuditoriaController::class, 'cambiarEstado'])
    ->name('admin.auditorias.cambiarEstado')
    ->middleware(['can:Auditorias']);

//Ruta para solicitud permiso equipo
Route::resource('/salidaequipos', SalidaEquiposController::class)
    ->names('admin.salidas');

//Ruta Gestion de Vehiculos
Route::get('/vehiculos/{vehiculo}/inspeccionar', [VehiculoController::class, 'inspeccionar'])
    ->name('admin.vehiculos.inspeccionar');
Route::resource('/vehiculos', VehiculoController::class)
    ->names('admin.vehiculos');
Route::resource('mantenimientos', MantenimientosVehiculosController::class)
    ->names('admin.mantenimientos');



Route::post('revisiones/{vehiculoinspeccion}/export-word', [RevisionesVehiculares::class, 'exportWord'])
    ->name('admin.revisiones.exportWord');

Route::get('revisiones/{vehiculoinspeccion}/export-word', [RevisionesVehiculares::class, 'exportWord'])
    ->name('admin.revisiones.exportWord.get');
Route::resource('revisiones', RevisionesVehiculares::class)
    ->parameters([
        'revisiones' => 'vehiculoinspeccion'
    ])
    ->names('admin.revisiones');

Route::resource('tecnicos', GestionInventarioController::class)->names('admin.tecnicos');
Route::get('desvinculacion/{desvinculacion}/acta-liberacion', [DesvinculacionController::class, 'generarActaLiberacion'])
    ->name('admin.desvinculacion.acta-liberacion');
Route::resource('desvinculacion', DesvinculacionController::class)->names('admin.desvinculacion');


Route::get('/dashboard/ejecutivo', ExecutiveDashboard::class)
    ->middleware(['can:Acceso al Dashboard'])
    ->name('admin.dashboard.ejecutivo');


/*
|--------------------------------------------------------------------------
| Módulo de Capacitación
|--------------------------------------------------------------------------
*/

Route::prefix('capacitacion')->group(function () {

    Route::get('/cursos', Index::class)
        ->middleware(['can:Gestion de Capacitacion'])
        ->name('admin.capacitacion.courses.index');

    Route::get('/cursos/crear', Create::class)
        ->middleware(['can:Gestion de Capacitacion'])
        ->name('admin.capacitacion.courses.create');

    Route::get('/cursos/{course}/editar', Edit::class)
        ->middleware(['can:Gestion de Capacitacion'])
        ->name('admin.capacitacion.courses.edit');

    Route::get('/cursos/{course}', Show::class)
        ->middleware(['can:Gestion de Capacitacion'])
        ->name('admin.capacitacion.courses.show');

    // Ruta para gestionar el Quiz del Módulo
    Route::get('/modulos/{module}/quiz', QuizBuilder::class)
        ->middleware(['can:Gestion de Capacitacion'])
        ->name('admin.capacitacion.modules.quiz');
    
});
Route::get('/enrollments', EnrollmentsIndex::class)->name('admin.enrollments.index');

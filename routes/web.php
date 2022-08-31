<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/guardar-actividad', [ProyectoController::class, 'store'])
    ->name('guardar-actividad');

Route::post('/guardar-incidencia', [ActividadController::class, 'store'])
    ->name('guardar-incidencia');

Route::post('/asignar-usuario-proyecto', [UsuarioController::class, 'asignarUsuarioProyecto'])
    ->name('asignar-usuario-proyecto');

Route::post('/asignar-usuario-actividad', [UsuarioController::class, 'asignarUsuarioActividad'])
    ->name('asignar-usuario-actividad');

Route::post('/asignar-usuario-incidencia', [UsuarioController::class, 'asignarUsuarioIncidencia'])
    ->name('asignar-usuario-incidencia');

Route::get('/listar-actividades-usuario/{id}', [UsuarioController::class, 'listarActividadesUsuario'])
    ->name('listar-actividades-usuario');

Route::get('/listar-incidencias-acceso/{id}', [UsuarioController::class, 'listarIncidenciasAccesoUsuario'])
    ->name('listar-incidencias-acceso');

Route::get('/listar-participantes-proyecto/{id}', [UsuarioController::class, 'listarParticipantesProyecto'])
    ->name('listar-participantes-proyecto');

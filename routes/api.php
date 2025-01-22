<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\EstudianteController;
use App\Http\Controllers\Api\InscripcionController;
use App\Http\Controllers\Api\PeriodoAcademicoController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('inscripciones/reportePorEstudiante/{id}', [InscripcionController::class, 'reportePorEstudiante'])->name('inscripciones.reportePorEstudiante');
Route::get('cursos/{id}/exportar-excel', [CursoController::class, 'exportarExcel'])->name('cursos.exportar-excel');

Route::middleware('auth:api')->group(function () {

Route::get('/user', [AuthController::class, 'user']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::prefix('periodos')->group(function () {
    Route::get('/', [PeriodoAcademicoController::class, 'index'])->name('periodos.all');
    Route::post('/', [PeriodoAcademicoController::class, 'store'])->name('periodos.store');
    Route::get('/{id}', [PeriodoAcademicoController::class, 'show'])->name('periodos.show');
    Route::put('/{id}', [PeriodoAcademicoController::class, 'update'])->name('periodos.update');
    Route::delete('/{id}', [PeriodoAcademicoController::class, 'destroy'])->name('periodos.destroy');
});

Route::prefix('cursos')->group(function () {
    Route::get('/', [CursoController::class, 'index'])->name('cursos.all');
    Route::post('/', [CursoController::class, 'store'])->name('cursos.store');
    Route::get('/{id}', [CursoController::class, 'show'])->name('cursos.show');
    Route::put('/{id}', [CursoController::class, 'update'])->name('cursos.update');
    Route::delete('/{id}', [CursoController::class, 'destroy'])->name('cursos.destroy');
    Route::get('/cursos-disponibles/{id}', [CursoController::class, 'disponibles'])->name('cursos.disponibles');
});

Route::prefix('estudiantes')->group(function () {
    Route::get('/', [EstudianteController::class, 'index'])->name('estudiantes.all');
    Route::post('/', [EstudianteController::class, 'store'])->name('estudiantes.store');
    Route::get('/{id}', [EstudianteController::class, 'show'])->name('estudiantes.show');
    Route::put('/{id}', [EstudianteController::class, 'update'])->name('estudiantes.update');
    Route::delete('/{id}', [EstudianteController::class, 'destroy'])->name('estudiantes.destroy');
    Route::get('/{id}/cursos-inscritos', [EstudianteController::class, 'cursosInscritos'])->name('estudiantes.cursos-inscritos');
});

Route::prefix('inscripciones')->group(function () {
    Route::post('/', [InscripcionController::class, 'inscribir'])->name('inscripciones.inscribir');

});
});
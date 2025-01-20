<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\PeriodoAcademicoController;

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
});
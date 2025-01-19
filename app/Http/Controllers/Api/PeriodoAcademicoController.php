<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PeriodoAcademico;
use Illuminate\Http\Request;

class PeriodoAcademicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodos = PeriodoAcademico::all();
        return response()->json([
            'success' => true,
            'data' => $periodos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
        ]);

        $periodo = PeriodoAcademico::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Período académico creado exitosamente',
            'data' => $periodo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $periodo = PeriodoAcademico::find($id);

        if (!$periodo) {
            return response()->json([
                'success' => false,
                'message' => 'Período académico no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $periodo
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $periodo = PeriodoAcademico::find($id);

        if (!$periodo) {
            return response()->json([
                'success' => false,
                'message' => 'Período académico no encontrado'
            ], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'fecha_inicio' => 'sometimes|required|date',
            'fecha_fin' => 'sometimes|required|date|after:fecha_inicio',
        ]);

        $periodo->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Período académico actualizado exitosamente',
            'data' => $periodo
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $periodo = PeriodoAcademico::find($id);

        if (!$periodo) {
            return response()->json([
                'success' => false,
                'message' => 'Período académico no encontrado'
            ], 404);
        }

        $periodo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Período académico eliminado exitosamente'
        ]);
    }
}

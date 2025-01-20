<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Listar todos los cursos.
     */
    public function index()
    {
        $cursos = Curso::all();
        return response()->json([
            'success' => true,
            'data' => $cursos
        ], 200);
    }

    /**
     * Crear un nuevo curso.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'codigo' => 'required|string|max:10',
            'nombre' => 'required|string|max:255',
            'docente' => 'required|string|max:255',
            'aula' => 'required|string|max:50',
            'dia' => 'required|string|max:20',
            'hora_inicio' => 'required|date_format:H:i:s',
            'hora_fin' => 'required|date_format:H:i:s|after:hora_inicio',
            'cupo' => 'required|integer|min:1',
            'periodo_academico_id' => 'required|exists:periodos_academicos,id',
        ]);

        $conflictingCurso = Curso::where('aula', $request->aula)
        ->where('dia', $request->dia)
        ->where(function ($query) use ($request) {
            $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                  ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                  ->orWhere(function ($query) use ($request) {
                      $query->where('hora_inicio', '<=', $request->hora_inicio)
                            ->where('hora_fin', '>=', $request->hora_fin);
                  });
        })->first();

        if ($conflictingCurso) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe un curso en el mismo aula con un horario traslapado.',
            ], 422);
        }

        $curso = Curso::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Curso creado con éxito',
            'data' => $curso
        ], 201);
    }

    /**
     * Mostrar un curso específico.
     */
    public function show($id)
    {
        $curso = Curso::find($id);

        if (!$curso) {
            return response()->json([
                'success' => false,
                'message' => 'Curso no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $curso
        ], 200);
    }

    /**
     * Actualizar un curso.
     */
    public function update(Request $request, $id)
    {
        $curso = Curso::find($id);

        if (!$curso) {
            return response()->json([
                'success' => false,
                'message' => 'Curso no encontrado'
            ], 404);
        }

        $validatedData = $request->validate([
            'codigo' => 'string|max:10|unique:cursos,codigo,' . $id,
            'nombre' => 'string|max:255',
            'docente' => 'string|max:255',
            'aula' => 'string|max:50',
            'dia' => 'string|max:20',
            'hora_inicio' => 'date_format:H:i:s',
            'hora_fin' => 'date_format:H:i:s|after:hora_inicio',
            'cupo' => 'integer|min:1',
            'periodo_academico_id' => 'exists:periodos_academicos,id',
        ]);

        $curso->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Curso actualizado con éxito',
            'data' => $curso
        ], 200);
    }

    /**
     * Eliminar un curso.
     */
    public function destroy($id)
    {
        $curso = Curso::find($id);

        if (!$curso) {
            return response()->json([
                'success' => false,
                'message' => 'Curso no encontrado'
            ], 404);
        }

        $curso->delete();

        return response()->json([
            'success' => true,
            'message' => 'Curso eliminado con éxito'
        ], 200);
    }
}

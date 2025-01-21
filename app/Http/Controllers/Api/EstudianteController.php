<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estudiante;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = Estudiante::orderBy('id')->get();

        return response()->json([
            'success' => true,
            'data' => $estudiantes
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'email' => 'required|email',
        ]);

        $codigo = Estudiante::latest('id')->value('codigo') + 1;

        $estudiante = Estudiante::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'email' => $request->email,
            'codigo' => $codigo
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estudiante creado exitosamente con codigo ' . $codigo,
            'data' => $estudiante
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $estudiante = Estudiante::find($id);

        if ($estudiante) {
            return response()->json([
                'success' => true,
                'data' => $estudiante
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Estudiante no encontrado'
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'email' => 'required|email',
        ]);

        $estudiante = Estudiante::find($id);

        if ($estudiante) {
            $estudiante->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Estudiante actualizado exitosamente',
                'data' => $estudiante
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Estudiante no encontrado'
        ], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $estudiante = Estudiante::find($id);

        if ($estudiante) {
            $estudiante->delete();

            return response()->json([
                'success' => true,
                'message' => 'Estudiante eliminado exitosamente'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Estudiante no encontrado'
        ], 404);
    }


    /**
     * Listar los cursos en los que está inscrito un estudiante.
     */
    public function cursosInscritos(string $id)
    {
        $estudiante = Estudiante::find($id);

        if ($estudiante) {
            $cursos = $estudiante->cursos;

            return response()->json([
                'success' => true,
                'data' => $cursos
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Estudiante no encontrado'
        ], 404);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\Estudiante;
use App\Models\Curso;
use PDF;

class InscripcionController extends Controller
{
    /**
     * Inscribir a un estudiante en un curso.
     */
    public function inscribir(Request $request)
    {
        $validatedData = $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $estudianteId = $validatedData['estudiante_id'];
        $cursoId = $validatedData['curso_id'];

        $curso = Curso::findOrFail($cursoId);

        if (Inscripcion::where('curso_id', $cursoId)->count() >= $curso->cupo) {
            throw ValidationException::withMessages([
                'curso_id' => 'El curso ya ha alcanzado el número máximo de inscripciones permitidas.',
            ]);
        }

        $inscripciones = Inscripcion::where('estudiante_id', $estudianteId)
            ->with('curso')
            ->get();

        foreach ($inscripciones as $inscripcion) {
            $cursoExistente = $inscripcion->curso;

            if (
                $cursoExistente->dia === $curso->dia &&
                (
                    ($curso->hora_inicio >= $cursoExistente->hora_inicio && $curso->hora_inicio < $cursoExistente->hora_fin) ||
                    ($curso->hora_fin > $cursoExistente->hora_inicio && $curso->hora_fin <= $cursoExistente->hora_fin) ||
                    ($curso->hora_inicio <= $cursoExistente->hora_inicio && $curso->hora_fin >= $cursoExistente->hora_fin)
                )
            ) {
                throw ValidationException::withMessages([
                    'curso_id' => 'El estudiante ya está inscrito en otro curso con horario que se superpone.',
                ]);
            }
        }

        $inscripcion = Inscripcion::create([
            'estudiante_id' => $estudianteId,
            'curso_id' => $cursoId,
        ]);

        return response()->json([
            'message' => 'Inscripción realizada con éxito.',
            'data' => $inscripcion,
        ], 201);
    }

    public function reportePorEstudiante($estudianteId)
    {
        $estudiante = Estudiante::with(['inscripciones.curso'])->findOrFail($estudianteId);

        $pdf = Pdf::loadView('reportes.estudiante', [
            'estudiante' => $estudiante,
            'cursos' => $estudiante->inscripciones->pluck('curso'),
        ]);

        return $pdf->stream("reporte_estudiante_{$estudiante->matricula}.pdf");
    }
}

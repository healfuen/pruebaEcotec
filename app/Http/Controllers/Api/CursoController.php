<?php

namespace App\Http\Controllers\Api;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\Curso;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
                'message' => 'Ya existe un curso en el mismo aula con un horario superpuesto.',
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

        $conflictingCurso = Curso::where('aula', $request->aula)
            ->where('dia', $request->dia)
            ->where('id', '!=', $id)
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
                'message' => 'Ya existe un curso en el mismo aula con un horario superpuesto.',
            ], 422);
        }

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

    public function disponibles($id)
    {
        $cursos = Curso::where('cupo', '>', 0)->get();
        $inscripciones = Inscripcion::where('estudiante_id', $id)->with('curso')->get();
        $cursosDisponibles = [];

        foreach ($cursos as $curso) {
            $disponible = true;

            foreach ($inscripciones as $inscripcion) {
                $cursoExistente = $inscripcion->curso;

                if (
                    $curso->dia === $cursoExistente->dia &&
                    (
                        ($curso->hora_inicio >= $cursoExistente->hora_inicio && $curso->hora_inicio < $cursoExistente->hora_fin) ||
                        ($curso->hora_fin > $cursoExistente->hora_inicio && $curso->hora_fin <= $cursoExistente->hora_fin) ||
                        ($curso->hora_inicio <= $cursoExistente->hora_inicio && $curso->hora_fin >= $cursoExistente->hora_fin)
                    )
                ) {
                    $disponible = false;
                    break;
                }
            }

            $curso->inscritos = Inscripcion::where('curso_id', $curso->id)->count();

            if ($disponible) {
                $cursosDisponibles[] = $curso;
            }
        }

        return response()->json([
            'success' => true,
            'data' => $cursosDisponibles
        ], 200);
    }

    /**
     * Exportar detalles de un curso a Excel.
     */
    public function exportarExcel($cursoId)
    {
        $curso = Curso::with(['inscripciones.estudiante'])->findOrFail($cursoId);

        return Excel::download(new class($curso) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithStyles, \Maatwebsite\Excel\Concerns\WithTitle {
            private $curso;

            public function __construct($curso)
            {
                $this->curso = $curso;
            }

            public function array(): array
            {
                $data = [];

                $data[] = ["Código:", $this->curso->codigo, " ", "Estadísticas de Inscripciones"];
                $data[] = ["Nombre del Curso:", $this->curso->nombre, " ", "Total Inscritos:", $this->curso->inscripciones->count()];
                $data[] = ["Aula:", $this->curso->aula, " ", "Lugares Disponibles:", $this->curso->cupo - $this->curso->inscripciones->count()];
                $data[] = ["Docente:", $this->curso->docente];
                $data[] = [""];

                $data[] = ["Matrícula del Estudiante", "Nombre del Estudiante"];

                foreach ($this->curso->inscripciones as $inscripcion) {
                    $data[] = [
                        $inscripcion->estudiante->codigo ?? 'N/A',
                        $inscripcion->estudiante->nombre ?? 'N/A',
                    ];
                }

                return $data;
            }

            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
            {
                $sheet->getColumnDimension('A')->setWidth(20);
                $sheet->getColumnDimension('B')->setWidth(40);
                $sheet->getColumnDimension('D')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(15);

                $sheet->getStyle('A1:E50')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A1:B4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFD700'],
                    ],
                ]);

                $sheet->getStyle('D1:E3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => '87CEEB'],
                    ],
                ]);

                $sheet->getStyle('A6:B6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => '4CAF50'],
                    ],
                ]);

            }

            public function title(): string
            {
                return 'Reporte del Curso';
            }
        }, "curso_{$curso->codigo}.xlsx");
    }
}

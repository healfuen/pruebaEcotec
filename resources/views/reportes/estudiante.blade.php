<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte del Estudiante</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Reporte del Estudiante</h1>
    <p><strong>Nombre:</strong> {{ $estudiante->nombre }} {{ $estudiante->apellido }}</p>
    <p><strong>Matrícula:</strong> {{ $estudiante->codigo }}</p>
    <h2>Cursos Inscritos</h2>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Aula</th>
                <th>Horario</th>
                <th>Docente</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cursos as $curso)
                <tr>
                    <td>{{ $curso->codigo }}</td>
                    <td>{{ $curso->nombre }}</td>
                    <td>{{ $curso->aula }}</td>
                    <td>{{ $curso->dia }} ({{ $curso->hora_inicio }} - {{ $curso->hora_fin }})</td>
                    <td>{{ $curso->docente }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p><strong>Total de Cursos Inscritos:</strong> {{ $cursos->count() }}</p>
</body>
</html>

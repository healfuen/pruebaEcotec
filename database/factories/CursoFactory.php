<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Curso>
 */
class CursoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $horaInicio = $this->faker->time('H:i');
        $horaFin = date('H:i', strtotime($horaInicio . ' +2 hours'));

        return [
            'codigo' => $this->faker->unique()->bothify('C-###'),
            'nombre' => $this->faker->words(3, true),
            'docente' => $this->faker->name,
            'aula' => $this->faker->bothify('A-##'),
            'dia' => $this->faker->randomElement(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']),
            'hora_inicio' => $horaInicio,
            'hora_fin' => $horaFin,
            'cupo' => $this->faker->numberBetween(10, 50),
            'periodo_academico_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}

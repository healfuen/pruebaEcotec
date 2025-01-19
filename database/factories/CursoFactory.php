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
        $horaInicio = $this->faker->time('now', '+2 hours');
        $horaFin = (clone $horaInicio)->modify('+2 hours');

        return [
            'codigo' => $this->faker->unique()->bothify('CURSO ###'),
            'nombre' => $this->faker->words(3, true),
            'docente' => $this->faker->name,
            'aula' => $this->faker->bothify('Aula ##'),
            'dia' => $this->faker->randomElement(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']),
            'hora_inicio' => $this->faker->time('H:i'),
            'hora_fin' => $this->faker->time('H:i'),
            'cupo' => $this->faker->numberBetween(10, 50),
            'periodo_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}

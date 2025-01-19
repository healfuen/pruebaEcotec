<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PeriodoAcademico>
 */
class PeriodoAcademicoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaInicio = $this->faker->dateTimeBetween('-1 year', 'now');
        $fechaFin = (clone $fechaInicio)->modify('+2 years');

        return [
            'nombre' => 'Periodo ' . $this->faker->year . ' - ' . $this->faker->randomDigitNotNull,
            'fecha_inicio' => $fechaInicio->format('Y-m-d'),
            'fecha_fin' => $fechaFin->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

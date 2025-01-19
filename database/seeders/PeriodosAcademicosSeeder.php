<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PeriodoAcademico;
use Illuminate\Database\Seeder;

class PeriodosAcademicosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PeriodoAcademico::factory()->count(10)->create();
    }
}

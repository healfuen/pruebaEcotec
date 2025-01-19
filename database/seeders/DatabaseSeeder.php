<?php

namespace Database\Seeders;

use Database\Seeders\PeriodosAcademicosSeeder;
use Database\Seeders\CursoSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PeriodosAcademicosSeeder::class,
            CursoSeeder::class,
        ]);
    }
}

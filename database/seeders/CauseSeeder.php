<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cause;

class CauseSeeder extends Seeder
{
    public function run(): void
    {
        $causes = [
            [
                'title' => 'Salud',
                'slug' => 'salud',
                'short_description' => 'Apoyo para tratamientos, medicamentos y atención médica para personas vulnerables.',
                'problem_description' => 'Aquí describirás a detalle la problemática de salud.',
                'use_of_funds' => 'Aquí explicarás cómo se usará lo recaudado (medicinas, consultas, etc.).',
                'since_date' => '2024-01-01',
                'is_active' => true,
            ],
            [
                'title' => 'Educación',
                'slug' => 'educacion',
                'short_description' => 'Becas, útiles escolares y apoyo educativo para niños y jóvenes.',
                'problem_description' => 'Aquí describirás la falta de recursos educativos.',
                'use_of_funds' => 'Aquí explicarás el destino (becas, materiales, transporte).',
                'since_date' => '2024-01-01',
                'is_active' => true,
            ],
            [
                'title' => 'Alimentos',
                'slug' => 'alimentos',
                'short_description' => 'Despensas, comedores comunitarios y apoyo alimentario para familias.',
                'problem_description' => 'Aquí describirás la inseguridad alimentaria.',
                'use_of_funds' => 'Aquí explicarás el destino (despensas, comedores, logística).',
                'since_date' => '2024-01-01',
                'is_active' => true,
            ],
        ];

        foreach ($causes as $cause) {
            Cause::updateOrCreate(['slug' => $cause['slug']], $cause);
        }
    }
}
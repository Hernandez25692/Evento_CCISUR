<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Capacitacion;

class CapacitacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $capacitaciones = [
            ['nombre' => 'Capacitación en Marketing Digital', 'lugar' => 'Sala 1', 'fecha' => '2025-04-01', 'impartido_por' => 'Carlos López', 'descripcion' => 'Aprende estrategias digitales.', 'imagen' => 'marketing.jpg'],
            ['nombre' => 'Gestión de Proyectos Ágiles', 'lugar' => 'Sala 2', 'fecha' => '2025-04-05', 'impartido_por' => 'Ana Pérez', 'descripcion' => 'Scrum, Kanban y metodologías ágiles.', 'imagen' => 'proyectos.jpg'],
            ['nombre' => 'Desarrollo Web con Laravel', 'lugar' => 'Sala 3', 'fecha' => '2025-04-10', 'impartido_por' => 'José Ramírez', 'descripcion' => 'Crea aplicaciones web con Laravel.', 'imagen' => 'laravel.jpg'],
            ['nombre' => 'Ciberseguridad y Protección de Datos', 'lugar' => 'Sala 4', 'fecha' => '2025-04-15', 'impartido_por' => 'María Gómez', 'descripcion' => 'Aprende a proteger la información.', 'imagen' => 'ciberseguridad.jpg'],
            ['nombre' => 'Inteligencia Artificial en los Negocios', 'lugar' => 'Sala 5', 'fecha' => '2025-04-20', 'impartido_por' => 'Fernando Díaz', 'descripcion' => 'Uso de IA en la industria.', 'imagen' => 'ia.jpg'],
        ];

        foreach ($capacitaciones as $capacitacion) {
            Capacitacion::create($capacitacion);
        }
    }
}

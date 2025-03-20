<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Participante;
use App\Models\Capacitacion;

class ParticipanteSeeder extends Seeder
{
    public function run()
    {
        $capacitaciones = Capacitacion::all(); // Obtener todas las capacitaciones

        // Definir los valores posibles para nivel_educativo y genero
        $niveles = ['Primaria', 'Secundaria', 'Universidad', 'Postgrado'];
        $generos = ['Masculino', 'Femenino', 'Otro'];

        foreach ($capacitaciones as $capacitacion) {
            for ($i = 1; $i <= 10; $i++) {
                Participante::create([
                    'nombre_completo' => "Participante $i en {$capacitacion->nombre}",
                    'correo' => "participante{$i}_cap{$capacitacion->id}@email.com", // Asegurar correos únicos
                    'telefono' => "99999999" . rand(0, 9),
                    'empresa' => "Empresa " . rand(1, 5),
                    'puesto' => "Puesto " . rand(1, 5),
                    'edad' => rand(20, 50),
                    'identidad' => "08011990" . rand(1000, 9999), // Evitar duplicados en identidad
                    'nivel_educativo' => $niveles[array_rand($niveles)],
                    'genero' => $generos[array_rand($generos)],
                    'municipio' => "Municipio " . rand(1, 5),
                    'ciudad' => "Ciudad " . rand(1, 5),
                    'capacitacion_id' => $capacitacion->id,
                ]);
            }
        }
    }
}

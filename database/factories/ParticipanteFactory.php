<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participante>
 */
class ParticipanteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_completo' => fake()->name(),
            'correo' => fake()->unique()->safeEmail(),
            'telefono' => fake()->phoneNumber(),
            'empresa' => fake()->company(),
            'puesto' => fake()->jobTitle(),
            'edad' => fake()->numberBetween(18, 65),
            'identidad' => fake()->unique()->numerify('####-####-#####'),
            'nivel_educativo' => 'Universitaria Completa',
            'genero' => fake()->randomElement(['Masculino', 'Femenino', 'Otro']),
            'municipio' => fake()->city(),
            'ciudad' => fake()->city(),
            'afiliado' => false,
        ];
    }
}

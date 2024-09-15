<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Persona;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Persona>
 */
class PersonaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'telefono' => fake()->e164PhoneNumber(),
        ];
    }

    public function filterTest(string $letter, string $extension): PersonaFactory|Factory
    {
        return $this->state(function (array $attributes) use ($letter, $extension){
            return [
                'nombre' => "Test " . $letter,
                'email' => Str::random(5). $extension,
            ];
        });
    }
}

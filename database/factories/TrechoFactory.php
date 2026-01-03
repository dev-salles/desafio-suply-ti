<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trecho>
 */
class TrechoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'data_referencia' => now(),
            'uf_id' => \App\Models\Uf::inRandomOrder()->first()->id, // Pega uma UF aleatória
            'rodovia_id' => \App\Models\Rodovia::inRandomOrder()->first()->id, // Pega uma Rodovia aleatória
            'km_inicial' => $start = fake()->numberBetween(0, 100),
            'km_final' => $start + fake()->numberBetween(1, 50),
        ];
    }
}

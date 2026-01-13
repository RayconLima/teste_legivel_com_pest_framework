<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bug>
 */
class BugFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence,
            'descricao' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['aberto', 'em progresso', 'resolvido', 'fechado']),
            'prioridade' => $this->faker->randomElement(['baixa', 'media', 'alta', 'critica']),
        ];
    }
}

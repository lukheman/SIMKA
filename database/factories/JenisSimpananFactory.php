<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisSimpanan>
 */
class JenisSimpananFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_simpanan' => $this->faker->words(2, true),
            'minimal_setor' => $this->faker->randomFloat(2, 10000, 1000000),
        ];
    }
}

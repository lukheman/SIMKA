<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisPinjaman>
 */
class JenisPinjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_pinjaman' => $this->faker->words(2, true),
            'bunga_persen' => $this->faker->randomFloat(2, 1, 15),
            'maks_tenor_bulan' => $this->faker->numberBetween(6, 60),
        ];
    }
}

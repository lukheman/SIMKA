<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Angsuran>
 */
class AngsuranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pengajuan_pinjaman_id' => \App\Models\PengajuanPinjaman::factory(),
            'angsuran_ke' => $this->faker->numberBetween(1, 60),
            'tgl_jatuh_tempo' => $this->faker->date(),
            'tgl_bayar' => $this->faker->optional()->date(),
            'jumlah_pokok' => $this->faker->randomFloat(2, 100000, 1000000),
            'jumlah_bunga' => $this->faker->randomFloat(2, 10000, 100000),
            'denda' => $this->faker->randomFloat(2, 0, 50000),
            'total_bayar' => $this->faker->optional()->randomFloat(2, 110000, 1150000),
            'status_bayar' => $this->faker->randomElement(['belum', 'lunas']),
        ];
    }
}

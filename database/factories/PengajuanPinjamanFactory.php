<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PengajuanPinjaman>
 */
class PengajuanPinjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'anggota_id' => \App\Models\Anggota::factory(),
            'jenis_pinjaman_id' => \App\Models\JenisPinjaman::factory(),
            'jumlah_pengajuan' => $this->faker->randomFloat(2, 1000000, 50000000),
            'jumlah_disetujui' => $this->faker->randomFloat(2, 1000000, 50000000),
            'tenor_bulan' => $this->faker->numberBetween(6, 60),
            'bunga_total' => $this->faker->randomFloat(2, 50000, 5000000),
            'status' => $this->faker->randomElement(['pending', 'disetujui', 'ditolak', 'lunas']),
            'tgl_pengajuan' => $this->faker->date(),
            'tgl_cair' => $this->faker->optional()->date(),
            'alasan_tolak' => $this->faker->optional()->sentence(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransaksiSimpanan>
 */
class TransaksiSimpananFactory extends Factory
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
            'jenis_simpanan_id' => \App\Models\JenisSimpanan::factory(),
            'kode_transaksi' => $this->faker->unique()->numerify('TRX-S-######'),
            'tipe_transaksi' => $this->faker->randomElement(\App\Enum\TipeTransaksi::values()),
            'jumlah' => $this->faker->randomFloat(2, 50000, 5000000),
            'tgl_transaksi' => $this->faker->date(),
            'keterangan' => $this->faker->sentence(),
            'petugas_id' => \App\Models\User::factory(),
        ];
    }
}

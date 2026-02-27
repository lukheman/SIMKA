<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Anggota>
 */
class AnggotaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'no_anggota' => $this->faker->unique()->numerify('AGT-#####'),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password',
            'nik' => $this->faker->unique()->numerify('1671##############'),
            'nama_lengkap' => $this->faker->name(),
            'alamat' => $this->faker->address(),
            'pekerjaan' => $this->faker->jobTitle(),
            'no_telp' => $this->faker->phoneNumber(),
            'tgl_bergabung' => $this->faker->date(),
            'status_aktif' => $this->faker->randomElement(\App\Enum\StatusAktif::values()),
        ];
    }
}

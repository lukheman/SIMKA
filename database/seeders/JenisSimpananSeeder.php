<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisSimpananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenis = ['Simpanan Pokok', 'Simpanan Wajib', 'Simpanan Hari Raya', 'Simpanan Sukarela'];

        foreach ($jenis as $nama) {
            \App\Models\JenisSimpanan::factory()->create([
                'nama_simpanan' => $nama,
                'minimal_setor' => rand(100, 1000) * 100,
            ]);
        }
    }
}

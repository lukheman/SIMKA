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
        $data = [
            ['nama_simpanan' => 'Simpanan Pokok', 'minimal_setor' => 100000],
            ['nama_simpanan' => 'Simpanan Wajib', 'minimal_setor' => 50000],
            ['nama_simpanan' => 'Simpanan Sukarela', 'minimal_setor' => 10000],
            ['nama_simpanan' => 'Simpanan Berjangka', 'minimal_setor' => 500000],
            ['nama_simpanan' => 'Simpanan Hari Raya', 'minimal_setor' => 25000],
        ];

        foreach ($data as $item) {
            \App\Models\JenisSimpanan::create($item);
        }
    }
}

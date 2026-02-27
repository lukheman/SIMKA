<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisPinjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_pinjaman' => 'Pinjaman Modal Usaha', 'bunga_persen' => 1.5, 'maks_tenor_bulan' => 36],
            ['nama_pinjaman' => 'Pinjaman Konsumtif', 'bunga_persen' => 2.0, 'maks_tenor_bulan' => 24],
            ['nama_pinjaman' => 'Pinjaman Pendidikan', 'bunga_persen' => 1.0, 'maks_tenor_bulan' => 48],
            ['nama_pinjaman' => 'Pinjaman Darurat', 'bunga_persen' => 2.5, 'maks_tenor_bulan' => 12],
            ['nama_pinjaman' => 'Pinjaman Perumahan', 'bunga_persen' => 1.2, 'maks_tenor_bulan' => 60],
        ];

        foreach ($data as $item) {
            \App\Models\JenisPinjaman::create($item);
        }
    }
}

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
        $jenis = ['Pinjaman Modal Usaha', 'Pinjaman Konsumtif', 'KPR', 'Kredit Kendaraan'];

        foreach ($jenis as $nama) {
            \App\Models\JenisPinjaman::factory()->create([
                'nama_pinjaman' => $nama,
                'bunga_persen' => rand(1, 10) + (rand(0, 9) / 10),
                'maks_tenor_bulan' => rand(6, 60),
            ]);
        }
    }
}

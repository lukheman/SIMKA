<?php

namespace Database\Seeders;

use App\Enum\StatusAktif;
use App\Models\Anggota;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        Anggota::factory()->create([
            'email' => 'anggota1@gmail.com',
            'password' => 'password123',
            'status_aktif' => StatusAktif::AKTIF
        ]);

        $this->call([
            AnggotaSeeder::class,
            JenisSimpananSeeder::class,
            TransaksiSimpananSeeder::class,
            JenisPinjamanSeeder::class,
            PengajuanPinjamanSeeder::class,
            AngsuranSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Enum\StatusAktif;
use App\Enum\UserRole;
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

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'password123',
            'role' => UserRole::ADMIN,
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'pimpinan@gmail.com',
            'password' => 'password123',
            'role' => UserRole::PIMPINAN,
        ]);

        $this->call([
            AnggotaSeeder::class,
            JenisSimpananSeeder::class,
            // TransaksiSimpananSeeder::class,
            JenisPinjamanSeeder::class,
            // PengajuanPinjamanSeeder::class,
            // AngsuranSeeder::class,
        ]);
    }
}

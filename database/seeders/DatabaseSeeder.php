<?php

namespace Database\Seeders;

use App\Enum\UserRole;
use App\Models\Anggota;
use App\Models\User;
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
            'password' => bcrypt('password123'),
            'role' => UserRole::ADMIN,
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'pimpinan@gmail.com',
            'password' => bcrypt('password123'),
            'role' => UserRole::PIMPINAN,
        ]);

        $this->call([
            AnggotaSeeder::class,
            JenisSimpananSeeder::class,
            JenisPinjamanSeeder::class,
            RealisticDataSeeder::class,
        ]);
    }
}

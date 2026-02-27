<?php

namespace App\Models;

use App\Enum\StatusAktif;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Anggota extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\AnggotaFactory> */
    use HasFactory;

    protected $table = 'anggota';

    protected $guarded = ['id'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'status_aktif' => StatusAktif::class,
            'password' => 'hashed',
        ];
    }

    public function transaksiSimpanan()
    {
        return $this->hasMany(TransaksiSimpanan::class);
    }

    public function pengajuanPinjaman()
    {
        return $this->hasMany(PengajuanPinjaman::class);
    }
}

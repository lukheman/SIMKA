<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    /** @use HasFactory<\Database\Factories\AnggotaFactory> */
    use HasFactory;

    protected $table = 'anggota';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status_aktif' => \App\Enum\StatusAktif::class,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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

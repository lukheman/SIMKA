<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPinjaman extends Model
{
    /** @use HasFactory<\Database\Factories\PengajuanPinjamanFactory> */
    use HasFactory;

    protected $table = 'pengajuan_pinjaman';

    protected $guarded = ['id'];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function jenisPinjaman()
    {
        return $this->belongsTo(JenisPinjaman::class);
    }

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class);
    }
}

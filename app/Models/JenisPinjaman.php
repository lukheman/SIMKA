<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPinjaman extends Model
{
    /** @use HasFactory<\Database\Factories\JenisPinjamanFactory> */
    use HasFactory;

    protected $table = 'jenis_pinjaman';

    protected $guarded = ['id'];

    public function pengajuanPinjaman()
    {
        return $this->hasMany(PengajuanPinjaman::class);
    }
}

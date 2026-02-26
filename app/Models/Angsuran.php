<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
    /** @use HasFactory<\Database\Factories\AngsuranFactory> */
    use HasFactory;

    protected $table = 'angsuran';

    protected $guarded = ['id'];

    public function pengajuanPinjaman()
    {
        return $this->belongsTo(PengajuanPinjaman::class);
    }
}

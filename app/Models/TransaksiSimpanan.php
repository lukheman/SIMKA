<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiSimpanan extends Model
{
    /** @use HasFactory<\Database\Factories\TransaksiSimpananFactory> */
    use HasFactory;

    protected $table = 'transaksi_simpanan';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tipe_transaksi' => \App\Enum\TipeTransaksi::class,
            'status' => \App\Enum\StatusPengajuan::class,
        ];
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function jenisSimpanan()
    {
        return $this->belongsTo(JenisSimpanan::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSimpanan extends Model
{
    /** @use HasFactory<\Database\Factories\JenisSimpananFactory> */
    use HasFactory;

    protected $table = 'jenis_simpanan';

    protected $guarded = ['id'];

    public function transaksiSimpanan()
    {
        return $this->hasMany(TransaksiSimpanan::class);
    }
}

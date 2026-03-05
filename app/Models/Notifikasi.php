<?php

namespace App\Models;

use App\Enum\TipeNotifikasi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tipe' => TipeNotifikasi::class,
            'dibaca' => 'boolean',
        ];
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function scopeBelumDibaca($query)
    {
        return $query->where('dibaca', false);
    }
}

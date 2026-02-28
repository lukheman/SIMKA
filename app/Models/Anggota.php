<?php

namespace App\Models;

use App\Enum\StatusAktif;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function hasAvatar(): bool
    {
        return !empty($this->avatar);
    }

    public function avatarUrl(): ?string
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }
        return null;
    }

    public function initials(): string
    {
        return Str::of($this->nama_lengkap)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
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

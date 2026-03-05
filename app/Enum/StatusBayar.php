<?php

namespace App\Enum;

enum StatusBayar: string
{
    case BELUM = 'belum';
    case MENUNGGU = 'menunggu';
    case LUNAS = 'lunas';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::BELUM => 'Belum Lunas',
            self::MENUNGGU => 'Menunggu Verifikasi',
            self::LUNAS => 'Lunas',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::BELUM => 'warning',
            self::MENUNGGU => 'primary',
            self::LUNAS => 'success',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::BELUM => 'fas fa-hourglass-half',
            self::MENUNGGU => 'fas fa-clock',
            self::LUNAS => 'fas fa-check-circle',
        };
    }
}

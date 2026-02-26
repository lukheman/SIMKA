<?php

namespace App\Enum;

enum StatusAktif: string
{
    case AKTIF = 'aktif';
    case PASIF = 'pasif';
    case KELUAR = 'keluar';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::AKTIF => 'Aktif',
            self::PASIF => 'Pasif',
            self::KELUAR => 'Keluar',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::AKTIF => 'success',
            self::PASIF => 'warning',
            self::KELUAR => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::AKTIF => 'fas fa-check-circle',
            self::PASIF => 'fas fa-pause-circle',
            self::KELUAR => 'fas fa-times-circle',
        };
    }
}

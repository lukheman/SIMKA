<?php

namespace App\Enum;

enum UserRole: string
{
    case ADMIN = 'admin';
    case ANGGOTA = 'anggota';
    case PIMPINAN = 'pimpinan';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::ANGGOTA => 'Anggota',
            self::PIMPINAN => 'Pimpinan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ADMIN => 'primary',
            self::ANGGOTA => 'info',
            self::PIMPINAN => 'warning',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::ADMIN => 'fas fa-shield-alt',
            self::ANGGOTA => 'fas fa-user',
            self::PIMPINAN => 'fas fa-user-tie',
        };
    }
}

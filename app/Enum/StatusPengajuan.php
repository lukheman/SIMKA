<?php

namespace App\Enum;

enum StatusPengajuan: string
{
    case PENDING = 'pending';
    case DISETUJUI = 'disetujui';
    case DITOLAK = 'ditolak';
    case LUNAS = 'lunas';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak',
            self::LUNAS => 'Lunas',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::DISETUJUI => 'success',
            self::DITOLAK => 'danger',
            self::LUNAS => 'success',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'fas fa-clock',
            self::DISETUJUI => 'fas fa-check',
            self::DITOLAK => 'fas fa-times',
            self::LUNAS => 'fas fa-check-double',
        };
    }
}

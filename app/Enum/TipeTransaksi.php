<?php

namespace App\Enum;

enum TipeTransaksi: string
{
    case SETOR = 'setor';
    case TARIK = 'tarik';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::SETOR => 'Setor',
            self::TARIK => 'Tarik',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SETOR => 'success',
            self::TARIK => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SETOR => 'fas fa-arrow-down',
            self::TARIK => 'fas fa-arrow-up',
        };
    }
}

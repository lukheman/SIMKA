<?php

namespace App\Enum;

enum TipeNotifikasi: string
{
    case INFO = 'info';
    case SUKSES = 'sukses';
    case PERINGATAN = 'peringatan';
    case BAHAYA = 'bahaya';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::INFO => 'Informasi',
            self::SUKSES => 'Sukses',
            self::PERINGATAN => 'Peringatan',
            self::BAHAYA => 'Penting',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::INFO => 'primary',
            self::SUKSES => 'success',
            self::PERINGATAN => 'warning',
            self::BAHAYA => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::INFO => 'fas fa-info-circle',
            self::SUKSES => 'fas fa-check-circle',
            self::PERINGATAN => 'fas fa-exclamation-triangle',
            self::BAHAYA => 'fas fa-exclamation-circle',
        };
    }
}

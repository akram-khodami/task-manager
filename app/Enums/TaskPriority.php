<?php

namespace App\Enums;

enum TaskPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Critical = 'critical';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'کم',
            self::Medium => 'متوسط',
            self::High => 'بالا',
            self::Critical => 'بحرانی',
        };
    }

    public static function options(): array
    {
        return [
            self::Low->value => 'کم',
            self::Medium->value => 'متوسط',
            self::High->value => 'بالا',
            self::Critical->value => 'بحرانی',
        ];
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return array_values(self::options());
    }
}

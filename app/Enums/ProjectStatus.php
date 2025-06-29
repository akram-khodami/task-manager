<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'پروژه فعال',
            self::Inactive => 'پروژه غیرفعال',
            self::Completed => 'پروژه اتمام یافته',
        };
    }

    public static function options(): array
    {
        return [
            self::Active->value => 'پروژه فعال',
            self::Inactive->value => 'پروژه غیرفعال',
            self::Completed->value => 'پروژه اتمام یافته',
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

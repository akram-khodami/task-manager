<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Todo = 'todo';
    case InProgress = 'in_progress';
    case Done = 'done';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Todo => 'کار جدید',
            self::InProgress => 'در حال انجام',
            self::Done => 'انجام شده',
            self::Cancelled => 'لغو شده',
        };
    }

    public static function options(): array
    {
        return [
            self::Todo->value => 'کار جدید',
            self::InProgress->value => 'در حال انجام',
            self::Done->value => 'انجام شده',
            self::Cancelled->value => 'لغو شده',
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

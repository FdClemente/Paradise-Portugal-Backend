<?php

namespace App\Enum\Experience;

enum TicketType: string
{
    case MORNING = 'morning';
    case AFTERNOON = 'afternoon';
    case FULL_DAY = 'full_day';
    case FLEXIBLE = 'flexible';

    public function label(): string
    {
        return match ($this) {
            self::MORNING => __('filament.experience.ticket_type.morning'),
            self::AFTERNOON => __('filament.experience.ticket_type.afternoon'),
            self::FULL_DAY => __('filament.experience.ticket_type.full_day'),
            self::FLEXIBLE => __('filament.experience.ticket_type.flexible'),
        };
    }

    public static function options(): array
    {
        return array_combine(
            array_map(fn ($type) => $type->value, self::cases()),
            array_map(fn ($type) => $type->label(), self::cases())
        );
    }

    public static function getLabel(self $type): string
    {
        return $type->label();
    }

    public function getMobileLabel()
    {
        return match ($this) {
            self::MORNING => __('filament.experience.ticket_type.mobile.morning'),
            self::AFTERNOON => __('filament.experience.ticket_type.mobile.afternoon'),
            self::FULL_DAY => __('filament.experience.ticket_type.mobile.full_day'),
            self::FLEXIBLE => __('filament.experience.ticket_type.mobile.flexible'),
        };
    }
}

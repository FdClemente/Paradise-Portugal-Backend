<?php

namespace App\Enum\Dates;

enum Months: string
{
    case January = 'january';
    case February = 'february';
    case March = 'march';
    case April = 'april';
    case May = 'may';
    case June = 'june';
    case July = 'july';
    case August = 'august';
    case September = 'september';
    case October = 'october';
    case November = 'november';
    case December = 'december';

    public function label(): string
    {
        return __("months.{$this->value}");
    }
}

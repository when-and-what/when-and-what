<?php

namespace App\Enums;

enum UnitType: string
{
    case GENERAL     = 'general';
    case HEALTH      = 'health';
    case CURRENCY    = 'currency';
    case TIME        = 'time';
    case TIMER       = 'timer';
    case DISTANCE    = 'distance';
    case TEMPERATURE = 'temperature';
    case WEIGHT      = 'weight';
    case VOLUME      = 'volume';

    public function emoji(): string
    {
        return match($this) {
            self::GENERAL     => '🔢',
            self::HEALTH      => '❤️',
            self::CURRENCY    => '💰',
            self::TIME        => '⏱️',
            self::TIMER       => '⏲️',
            self::DISTANCE    => '📏',
            self::TEMPERATURE => '🌡️',
            self::WEIGHT      => '⚖️',
            self::VOLUME      => '🧪',
        };
    }
}

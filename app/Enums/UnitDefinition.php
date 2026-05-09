<?php

namespace App\Enums;

readonly class UnitDefinition
{
    public function __construct(
        public string $label,
        public string $plural,
        public string $symbol,
        public UnitType $type,
        public ?TrackerUnit $cousin = null,
    ) {
    }
}

<?php

namespace App\Enums;

enum TrackerUnit: string
{
    // General
    case NUM      = 'num';
    case STEPS    = 'steps';
    case BOTTLES  = 'bottles';
    case REPS     = 'reps';
    case PAGES    = 'pages';
    case PERCENT  = 'percent';
    case IU       = 'iu';

    // Health
    case TABS         = 'tabs';
    case CALORIE      = 'calorie';
    case KCAL         = 'kcal';
    case CAPSULES     = 'capsules';
    case BPM          = 'bpm';
    case PAIN         = 'pain';
    case BLOOD_SUGAR  = 'mdgl';

    // Currency
    case DOLLARS         = 'dollars';
    case PESO            = 'peso';
    case FRANC           = 'franc';
    case CURRENCY_POUND  = 'cpound';
    case RUPEE           = 'rupee';
    case YEN             = 'yen';
    case YUAN            = 'yuan';
    case BITCOIN         = 'bitcoin';
    case EURO            = 'euro';

    // Timer
    case TIMER = 'timer';

    // Time
    case SECOND = 'sec';
    case MINUTE = 'min';
    case HOUR   = 'hour';
    case DAY    = 'day';

    // Distance
    case MILLIMETER = 'mm';
    case CENTIMETER = 'cm';
    case METER      = 'meter';
    case KILOMETER  = 'km';
    case INCH       = 'inch';
    case FOOT       = 'foot';
    case YARD       = 'yard';
    case MILE       = 'mile';

    // Temperature
    case DEGREE      = 'degrees';
    case CELSIUS     = 'celsius';
    case KELVIN      = 'kelvin';
    case FAHRENHEIT  = 'fahrenheit';

    // Weight
    case MICROGRAM = 'mcg';
    case MILLIGRAM = 'mg';
    case GRAM      = 'gram';
    case KILOGRAM  = 'kg';
    case STONE     = 'stone';
    case OUNCE     = 'oz';
    case POUND     = 'pound';

    // Volume
    case CUP         = 'cup';
    case FLUID_OUNCE = 'fluidounce';
    case PINT        = 'pint';
    case DECILITER   = 'deciliter';
    case QUART       = 'quart';
    case GALLON      = 'gallon';
    case LITER       = 'liter';
    case KILOLITER   = 'kiloliter';
    case MILLILITER  = 'milliliter';

    public function definition(): UnitDefinition
    {
        return match($this) {
            // General
            self::NUM     => new UnitDefinition('Count',   'Count',   '',    UnitType::GENERAL),
            self::STEPS   => new UnitDefinition('Step',    'Steps',   'steps', UnitType::GENERAL),
            self::BOTTLES => new UnitDefinition('Bottle',  'Bottles', '',    UnitType::GENERAL),
            self::REPS    => new UnitDefinition('Rep',     'Reps',    '',    UnitType::GENERAL),
            self::PAGES   => new UnitDefinition('Page',    'Pages',   '',    UnitType::GENERAL),
            self::PERCENT => new UnitDefinition('Percent', 'Percent', '%',   UnitType::GENERAL),
            self::IU      => new UnitDefinition('International Unit', 'International Units', 'IU', UnitType::GENERAL),

            // Health
            self::TABS        => new UnitDefinition('Tab',              'Tabs',         '',      UnitType::HEALTH),
            self::CALORIE     => new UnitDefinition('Calorie',          'Calories',     'cal',   UnitType::HEALTH),
            self::KCAL        => new UnitDefinition('Kcal',             'Kcals',        'kcal',  UnitType::HEALTH),
            self::CAPSULES    => new UnitDefinition('Capsule',          'Capsules',     'caps',  UnitType::HEALTH),
            self::BPM         => new UnitDefinition('Beat Per Minute',  'Beat Per Minute', 'bpm', UnitType::HEALTH),
            self::PAIN        => new UnitDefinition('Pain',             'Pain',         '',      UnitType::HEALTH),
            self::BLOOD_SUGAR => new UnitDefinition('Blood Sugar',      'Blood Sugar',  'mg/dl', UnitType::HEALTH),

            // Currency
            self::DOLLARS        => new UnitDefinition('Dollar', 'Dollars', '$',   UnitType::CURRENCY),
            self::PESO           => new UnitDefinition('Peso',   'Peso',    '$',   UnitType::CURRENCY),
            self::FRANC          => new UnitDefinition('Franc',  'Francs',  'Fr.', UnitType::CURRENCY),
            self::CURRENCY_POUND => new UnitDefinition('Pound',  'Pounds',  '£',   UnitType::CURRENCY),
            self::RUPEE          => new UnitDefinition('Rupee',  'Rupees',  'Rs.', UnitType::CURRENCY),
            self::YEN            => new UnitDefinition('Yen',    'Yen',     '¥',   UnitType::CURRENCY),
            self::YUAN           => new UnitDefinition('Yuan',   'Yuan',    '¥',   UnitType::CURRENCY),
            self::BITCOIN        => new UnitDefinition('Bitcoin', 'Bitcoins', 'B', UnitType::CURRENCY),
            self::EURO           => new UnitDefinition('Euro',   'Euros',   '€',   UnitType::CURRENCY),

            // Timer
            self::TIMER => new UnitDefinition('Time', 'Time', '', UnitType::TIMER),

            // Time
            self::SECOND => new UnitDefinition('Second', 'Seconds', 'secs', UnitType::TIME),
            self::MINUTE => new UnitDefinition('Minute', 'Minutes', 'm',    UnitType::TIME),
            self::HOUR   => new UnitDefinition('Hour',   'Hours',   'hrs',  UnitType::TIME),
            self::DAY    => new UnitDefinition('Day',    'Days',    'days', UnitType::TIME),

            // Distance
            self::MILLIMETER => new UnitDefinition('Millimeter', 'Millimeters', 'mm',   UnitType::DISTANCE),
            self::CENTIMETER => new UnitDefinition('Centimeter', 'Centimeters', 'cm',   UnitType::DISTANCE),
            self::METER      => new UnitDefinition('Meter',      'Meters',      'm',    UnitType::DISTANCE),
            self::KILOMETER  => new UnitDefinition('Kilometer',  'Kilometers',  'km',   UnitType::DISTANCE, self::MILE),
            self::INCH       => new UnitDefinition('Inch',       'Inches',      'in',   UnitType::DISTANCE),
            self::FOOT       => new UnitDefinition('Foot',       'Feet',        'ft',   UnitType::DISTANCE),
            self::YARD       => new UnitDefinition('Yard',       'Yards',       'yrds', UnitType::DISTANCE),
            self::MILE       => new UnitDefinition('Mile',       'Miles',       'mi',   UnitType::DISTANCE, self::KILOMETER),

            // Temperature
            self::DEGREE     => new UnitDefinition('Degree',     'Degrees',     '°',   UnitType::TEMPERATURE),
            self::CELSIUS    => new UnitDefinition('Celsius',    'Celsius',     '°C',  UnitType::TEMPERATURE, self::FAHRENHEIT),
            self::KELVIN     => new UnitDefinition('Kelvin',     'Kelvin',      'K',   UnitType::TEMPERATURE),
            self::FAHRENHEIT => new UnitDefinition('Fahrenheit', 'Fahrenheit',  '°F',  UnitType::TEMPERATURE, self::CELSIUS),

            // Weight
            self::MICROGRAM => new UnitDefinition('Microgram', 'Micrograms', 'mcg', UnitType::WEIGHT),
            self::MILLIGRAM => new UnitDefinition('Milligram', 'Milligrams', 'mg',  UnitType::WEIGHT),
            self::GRAM      => new UnitDefinition('Gram',      'Grams',      'g',   UnitType::WEIGHT, self::OUNCE),
            self::KILOGRAM  => new UnitDefinition('Kilogram',  'Kilograms',  'kg',  UnitType::WEIGHT, self::POUND),
            self::STONE     => new UnitDefinition('Stone',     'Stones',     'st',  UnitType::WEIGHT),
            self::OUNCE     => new UnitDefinition('Ounce',     'Ounces',     'oz',  UnitType::WEIGHT, self::GRAM),
            self::POUND     => new UnitDefinition('Pound',     'Pounds',     'lbs', UnitType::WEIGHT, self::KILOGRAM),

            // Volume
            self::CUP         => new UnitDefinition('Cup',         'Cups',         'cups', UnitType::VOLUME),
            self::FLUID_OUNCE => new UnitDefinition('Fluid Ounce', 'Fluid Ounces', 'oz',  UnitType::VOLUME, self::MILLILITER),
            self::PINT        => new UnitDefinition('Pint',        'Pints',        'pint', UnitType::VOLUME),
            self::DECILITER   => new UnitDefinition('Deciliter',   'Deciliters',   'dL',  UnitType::VOLUME),
            self::QUART       => new UnitDefinition('Quart',       'Quarts',       'qt',  UnitType::VOLUME),
            self::GALLON      => new UnitDefinition('Gallon',      'Gallons',      'gal', UnitType::VOLUME, self::LITER),
            self::LITER       => new UnitDefinition('Liter',       'Liters',       'L',   UnitType::VOLUME, self::GALLON),
            self::KILOLITER   => new UnitDefinition('Kiloliter',   'Kiloliters',   'kl',  UnitType::VOLUME),
            self::MILLILITER  => new UnitDefinition('Milliliter',  'Milliliters',  'ml',  UnitType::VOLUME, self::FLUID_OUNCE),
        };
    }

    public function label(): string    { return $this->definition()->label; }
    public function plural(): string   { return $this->definition()->plural; }
    public function symbol(): string   { return $this->definition()->symbol; }
    public function type(): UnitType   { return $this->definition()->type; }
    public function cousin(): ?static  { return $this->definition()->cousin; }

    /** @return static[] */
    public static function forType(UnitType $type): array
    {
        return array_values(array_filter(self::cases(), fn ($u) => $u->type() === $type));
    }
}

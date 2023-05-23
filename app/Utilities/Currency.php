<?php

namespace App\Utilities;

class Currency
{
    public static $symbol = '£';

    public static function format(int|float $value): string
    {
        return self::$symbol . number_format($value, 2, '.', ',');
    }

    public static function formatRounded(int|float $value): string
    {
        return self::$symbol . number_format($value, 0, '.', ',');
    }
}
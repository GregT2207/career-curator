<?php

namespace App\Utilities;

class Currency
{
    public static $symbol = '£';

    public static function format($value): string
    {
        return self::$symbol . number_format($value, 2, '.', ',');
    }

    public static function formatRounded($value): string
    {
        return self::$symbol . number_format($value, 0, '.', ',');
    }
}
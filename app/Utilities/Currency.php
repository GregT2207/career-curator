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

    public static function extractMoneyValues(string $text): array
    {
        $values = [];

        $strings = [];
        while (preg_match('/' . self::$symbol . '([^ ]+)/', $text, $matches)) {
            $strings[] = $matches[0];
            $values[] = floatval(preg_replace('/[^0-9£.]+/', '', $matches[1]));

            $text = str_replace($matches[0], '', $text);
        }

        return $values;
    }
}
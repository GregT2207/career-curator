<?php

namespace App\Utilities;

class Arrays
{
    // takes a 2d array and builds a new 1d array by taking items from each original array one at a time
    public static function mergeInterspersed(array $arrays): array
    {
        $interspersed = [];

        $longest = 0;
        foreach ($arrays as $array) {
            if (count($array) >= $longest) {
                $longest = count($array);
            }
        }

        for ($i = 0; $i < $longest; $i++) {
            foreach ($arrays as $array) {
                if (array_key_exists($i, $array)) {
                    $interspersed[] = $array[$i];
                }
            }
        }

        return $interspersed;
    }
}
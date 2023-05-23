<?php

namespace App\Utilities;

class Arrays
{
    // takes a 2d array and builds a new 1d array by taking items from each original array one at a time
    public static function mergeInterspersed(array $arrays): array
    {
        $interspersedLinks = [];

        $index = 0;
        foreach ($arrays as $array) {
            foreach ($array as $link) {
                $interspersedLinks[] = $link;
            }

            $index++;
        }

        return $interspersedLinks;
    }
}
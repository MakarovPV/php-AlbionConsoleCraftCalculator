<?php

namespace App\Utils;

class Calculate
{
    public static function calculate(int $countItems, int $costItem)
    {
        return $countItems * $costItem;
    }
}
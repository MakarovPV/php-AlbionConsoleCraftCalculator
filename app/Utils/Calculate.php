<?php

namespace App\Utils;

class Calculate
{
    public static function calculate(int $countItems, int $costItem)
    {
        return $countItems * $costItem;
    }

    public static function percentageBalance(float $firstNumber, float $secondNumber)
    {
        if ($secondNumber == 0) {
            return "Некорректные данные.";
        }

        $difference = floor((($firstNumber - $secondNumber) / $secondNumber) * 1000) / 10;

        return $difference;
    }
}
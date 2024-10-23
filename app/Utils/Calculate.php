<?php

namespace App\Utils;

class Calculate
{
    /**
     * Без комментариев.
     * @param int $countItems
     * @param int $costItem
     * @return int
     */
    public static function calculate(int $countItems, int $costItem): int
    {
        return $countItems * $costItem;
    }

    /**
     * Вычисление процентной разницы между 2 значениями.
     * @param float $firstNumber
     * @param float $secondNumber
     * @return float|int|string
     */
    public static function percentageBalance(float $firstNumber, float $secondNumber): float|int|string
    {
        if ($secondNumber == 0) {
            return "Некорректные данные.";
        }

        $difference = floor((($firstNumber - $secondNumber) / $secondNumber) * 1000) / 10;

        return $difference;
    }
}
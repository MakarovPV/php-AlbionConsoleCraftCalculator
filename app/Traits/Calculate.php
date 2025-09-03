<?php

namespace App\Traits;

trait Calculate
{
    /**
     * Без комментариев.
     * @param int $countItems
     * @param int $costItem
     * @return int
     */
    protected function calculate(int ...$args): int
    {
        return array_product($args);
    }

    /**
     * Вычисление процентной разницы между 2 значениями.
     * @param float $firstNumber
     * @param float $secondNumber
     * @return float|int|string
     */
    protected function percentageBalance(float $firstNumber, float $secondNumber): float|int|string
    {
        if ($secondNumber == 0) {
            return "Некорректные данные.";
        }

        $difference = floor((($firstNumber - $secondNumber) / $secondNumber) * 1000) / 10;

        return $difference;
    }

    protected function calculateCostWithReturn(int $totalCost, float $returnPercent): float
    {
        $multiplier = 1 - ($returnPercent / 100);
        return $totalCost * $multiplier;
    }
}
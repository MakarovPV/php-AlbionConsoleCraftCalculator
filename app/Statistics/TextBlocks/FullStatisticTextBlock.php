<?php

namespace App\Statistics\TextBlocks;

use App\Percents\ReturnPercent;

class FullStatisticTextBlock extends TextBlock
{
    public function countOfItems(array $data): void
    {
        $this->text .= "Требуется предмет "
            . $data['uniqueName'] . " в количестве "
            . $data['countOfIteration'] . " единиц, с общей стоимостью в "
            . $data['costItem'] . " серебра. \n";
    }

    public function totalCost(array $data): void
    {
        foreach (ReturnPercent::all() as $percent) {
            $cost = $this->calculateCostWithReturn(
                $data['totalCost'],
                $percent
            );

            $profit = $this->percentageBalance(
                $data['mainItemCost'] * $data['countOfIteration'] * 0.935,
                $data['totalCost'] * (1 - $percent / 100)
            );

            $this->text .= "Общая стоимость с " . $percent . "% возвратом ресурсов: "
                . $cost . " серебра. "
                . "Примерный профит составляет {$profit}%\n";
        }
    }
}
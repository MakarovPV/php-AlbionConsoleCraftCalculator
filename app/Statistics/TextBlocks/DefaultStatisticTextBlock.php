<?php

namespace App\Statistics\TextBlocks;

use App\Percents\TaxPercent;

class DefaultStatisticTextBlock extends TextBlock
{
    public function countOfItems(array $data): void
    {
        $this->text .= "\nПредмет " . $data['rusName'] . " крафтится в количестве " . $data['amountItems'] . " единиц." . "\n";
        $this->data['amountItems'] = $data['amountItems'];
    }

    public function totalCost(array $data): void
    {
        foreach (TaxPercent::all() as $percent) {
            $cost = $this->calculateCostWithReturn(
                $data['baseCost'],
                $percent
            );

            $this->text .= "Стоимость с " . $percent . "% налогом за " .
                $this->data['amountItems'] . " предметов в городе " .
                $data['city'] . ": " .
                $cost . " серебра.\n";
        }
    }
}
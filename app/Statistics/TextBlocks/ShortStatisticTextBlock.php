<?php

namespace App\Statistics\TextBlocks;

class ShortStatisticTextBlock extends TextBlock
{
    public function totalCost(array $data): void
    {
        $this->text .= $data['amountItemsPerIteration'] . " предметов "
            . $data['rusName'] . " в городе "
            . $data['city'] . " составляет "
            . $data['mainItemCost'] . " серебра.\n\n";
    }
}
<?php

namespace App\Statistics;

class ShortStatistic extends Statistic
{
    public function __construct(string $cityName)
    {
        parent::__construct();
        $this->cityName = $cityName;
    }

    /**
     * @inheritDoc
     */
    public function build(int $amountItems, array $namesOfMainItem): string
    {
        $this->mainItemCost = $this->calculate($amountItems, $this->getItemCostFromApi($namesOfMainItem['uniqueName'], $this->cityName));
        $str = 'Стоимость ' . $amountItems . ' предметов ' . $namesOfMainItem['rusName'] . ' в городе ' . $this->cityName . ' составляет ' . $this->mainItemCost . " серебра.\n";
        return $str;
    }
}
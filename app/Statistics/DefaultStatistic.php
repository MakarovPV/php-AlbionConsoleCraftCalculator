<?php

namespace App\Statistics;

use App\Utils\Calculate;

class DefaultStatistic extends Statistic
{
    public function dataOfMainItem(array $namesOfMainItem)
    {
        echo 'Стоимость одного предмета ' . $namesOfMainItem['rusName'] . ' составляет ' .
            Calculate::calculate(1, $this->getItemCostFromApi($namesOfMainItem['uniqueName'], $this->cityName)) . " серебра. \nПоиск данных по ресурсам для его создания:\n";
    }

    public function build()
    {
        $str = '';
        $totalCost = 0;
        foreach ($this->itemsArray as $uniqueName => $count){
            $costItem = Calculate::calculate($count, $this->getItemCostFromApi($uniqueName, $this->cityName));
            $totalCost += $costItem;
            $str .= 'Требуется предмет ' . $this->getRusNameFromElastic($uniqueName) .
            ' в количестве ' . $count . ' единиц, с общей стоимостью в ' .
            $costItem .
            " серебра. \n";
        }
        $str .= "Общая стоимость ресурсов: " . $totalCost . ' серебра.';
        return $str;
    }
}
<?php

namespace App\Statistics;


class DefaultStatistic extends Statistic
{
    /**
     * Получение данных по основному предмету и вывод их в консоль.
     * @param array $namesOfMainItem
     * @return void
     */
    public function dataOfMainItem(array $namesOfMainItem): void
    {
        $this->mainItemCost = $this->calculate(1, $this->getItemCostFromApi($namesOfMainItem['uniqueName'], $this->cityName));
        echo 'Стоимость одного предмета ' . $namesOfMainItem['rusName'] . ' составляет ' . $this->mainItemCost . "\n" .
            'Стоимость с 6.5% налогом: ' . $this->mainItemCost * 0.935 . " серебра.\n" .
            "Поиск данных по ресурсам для его создания:\n";

    }

    public function build(): string
    {
        $str = '';
        $totalCost = 0;
        foreach ($this->itemsArray as $uniqueName => $count){
            $costItem = $this->calculate($count, $this->getItemCostFromApi($uniqueName, $this->cityName));
            $totalCost += $costItem;
            $str .= 'Требуется предмет ' . $this->getRusNameFromElastic($uniqueName) .
            ' в количестве ' . $count . ' единиц, с общей стоимостью в ' .
            $costItem .
            " серебра. \n";
        }
        $str .= "Общая стоимость ресурсов: " . $totalCost . " серебра.\n" .
            'Общая стоимость с 24.8% возвратом ресурсов: ' . $totalCost * 0.752 . " серебра. Примерный профит составляет " . $this->percentageBalance($this->mainItemCost * 0.935, $totalCost * 0.752) . "%\n" .
            'Общая стоимость с 15.2% возвратом ресурсов: ' . $totalCost * 0.848 . " серебра. Примерный профит составляет " . $this->percentageBalance($this->mainItemCost * 0.935, $totalCost * 0.848) . "%\n";
        return $str;
    }
}
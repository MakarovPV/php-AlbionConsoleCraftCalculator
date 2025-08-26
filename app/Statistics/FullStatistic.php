<?php

namespace App\Statistics;

class FullStatistic extends Statistic
{
    public function __construct(string $cityName, array $itemsArray)
    {
        parent::__construct();
        $this->cityName = $cityName;
        $this->prev = new DefaultStatistic($cityName);
        $this->itemsArray = $itemsArray;
    }

    /**
     * @inheritDoc
     */
    public function build(int $amountItems, array $namesOfMainItem): string
    {
        $str = $this->prev->build($amountItems, $namesOfMainItem);
        $str .= "\nПоиск данных по ресурсам для его создания:\n";
        $totalCost = 0;
        foreach ($this->itemsArray as $uniqueName => $count){
            $costItem = $this->calculate($count, $this->getItemCostFromApi($uniqueName, $this->cityName));
            $totalCost += $costItem;
            $str .= 'Требуется предмет ' . $this->getRusNameFromElastic($uniqueName) .
                ' в количестве ' . $count . ' единиц, с общей стоимостью в ' .
                $costItem .
                " серебра. \n";
        }
        $str .= "\nОбщая стоимость ресурсов: " . $totalCost . " серебра.\n" .
            'Общая стоимость с 15.2% возвратом ресурсов: ' . $this->calculateCostWithReturn($totalCost, 15.2) . " серебра. Примерный профит составляет " . $this->percentageBalance($this->prev->prev->mainItemCost * 0.935, $totalCost * 0.848) . "%\n" .
            'Общая стоимость с 24.8% возвратом ресурсов: ' .$this->calculateCostWithReturn($totalCost, 24.8) . " серебра. Примерный профит составляет " . $this->percentageBalance($this->prev->prev->mainItemCost * 0.935, $totalCost * 0.752) . "%\n";
        return $str;
    }
}
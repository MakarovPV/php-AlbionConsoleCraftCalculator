<?php

namespace App\Statistics;

use App\Utils\Calculate;

class DefaultStatistic extends Statistic
{
    public function build()
    {
        $str = '';
        foreach ($this->itemsArray as $uniqueName => $count){
            $str .= 'Требуется предмет ' . $this->getRusNameFromElastic($uniqueName) .
            ' в количестве ' . $count . ' единиц, с общей стоимостью в ' .
            Calculate::calculate($count, $this->getItemCostFromApi($uniqueName, $this->cityName)) .
            " серебра. \n";
        }
       return $str;
    }
}
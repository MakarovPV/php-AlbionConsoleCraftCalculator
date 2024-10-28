<?php

namespace App\Traits;

trait TrimArray
{
    /**
     * Извлечение из строки названия предмета и его уровня.
     * @param array $array
     * @return array|null
     */
    protected function trimArrayForElastic(array $array): array|null
    {
        $trimArray = [];
        foreach (array_slice($array, 1) as $item){
            $trimArray[] = $item;
            if(is_numeric($item)) return $trimArray;
        }
        return null;
    }

    /**
     * Извлечение из строки типа статистики и названия города.
     * @param array $array
     * @return array
     */
    protected function getStatTypeAndCityName(array $array): array
    {
        $statTypeAndCityName = [];
        for($i=1; $i<count($array); $i++){
            if(is_numeric($array[$i])){
                $statTypeAndCityName = array_slice($array, $i+1, 2);
                break;
            }
        }
        return array_merge($statTypeAndCityName, array_fill(0, 2 - count($statTypeAndCityName), null));
    }
}
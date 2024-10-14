<?php

namespace App\Utils;

class TrimArray
{
    public static function trimArrayForElastic(array $array)
    {
        $trimArray = [];
        foreach (array_slice($array, 1) as $item){
            $trimArray[] = $item;
            if(is_numeric($item)) return $trimArray;
        }
    }

    public static function getStatTypeAndCityName(array $array)
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
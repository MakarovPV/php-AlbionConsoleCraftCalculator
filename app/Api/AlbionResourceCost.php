<?php

namespace App\Api;

use App\Traits\JsonDecoder;

class AlbionResourceCost
{
    use JsonDecoder;
    /**
     * Получение стоимости предмета.
     * @param string $itemName
     * @param string $cityName
     * @return mixed
     */
    public function getCostByItemName(string $itemName, string $cityName)
    {
        $itemName = $this->normalizeItemName($itemName);
        $url = "https://old.west.albion-online-data.com/api/v1/stats/Prices/$itemName.json?locations=$cityName";
        $dataFromApi = $this->getDataFromJson($url);

        return $dataFromApi[0]['sell_price_min'];
    }

    private function normalizeItemName(string $itemName): string
    {
        if(is_numeric($itemName[-1]) && $itemName[-2]!='@') $itemName .= '@' . $itemName[-1];
        return $itemName;
    }
}
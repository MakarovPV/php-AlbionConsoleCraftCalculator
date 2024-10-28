<?php

namespace App\Api;

use App\Traits\JsonDecoder;

class AlbionResourceCost
{
    use JsonDecoder;
    /**
     * Получение стоимости предмета.
     * @param string $resourceName
     * @param string $cityName
     * @return mixed
     */
    public function getCostByItemName(string $resourceName, string $cityName)
    {
        $url = "https://old.west.albion-online-data.com/api/v1/stats/Prices/$resourceName.json?locations=$cityName";
        $dataFromApi = $this->getDataFromJson($url);

        return $dataFromApi[0]['sell_price_min'];
    }
}
<?php

namespace App\Exceptions\City;

class CityWrongNameException extends \Exception
{
    public function __construct(string $cityName)
    {
        $this->message = "Город '{$cityName}' не найден.";
        parent::__construct($this->message);
    }
}
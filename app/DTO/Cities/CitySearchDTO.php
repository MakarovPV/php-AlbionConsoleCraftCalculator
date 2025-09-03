<?php

namespace App\DTO\Cities;

use App\DTO\DTO;

class CitySearchDTO implements DTO
{
    public function __construct(
        public string $cityName,
    ){}
}
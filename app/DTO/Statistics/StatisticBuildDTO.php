<?php

namespace App\DTO\Statistics;

use App\DTO\DTO;

class StatisticBuildDTO implements DTO
{
    public function __construct(
        public int $countOfIteration,
        public int $amountItemsPerIteration,
        public array $namesOfMainItem
    ){}
}
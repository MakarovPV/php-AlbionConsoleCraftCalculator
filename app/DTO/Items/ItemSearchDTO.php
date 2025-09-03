<?php

namespace App\DTO\Items;

use App\DTO\DTO;

class ItemSearchDTO implements DTO
{
    public function __construct(
        public array|string $itemNames,
        public string $tier,
        public string $enchant
    ) {
        $this->itemNames = is_string($this->itemNames) ? [$this->itemNames] : $this->itemNames;
    }
}
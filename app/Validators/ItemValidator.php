<?php

namespace App\Validators;

use App\Exceptions\Item\CountOfItemsEmptyException;
use App\Exceptions\Item\CountOfItemsIncorrectException;
use App\Exceptions\Item\ItemEmptyNameException;
use App\Exceptions\Item\ItemIncorrectEnchantException;
use App\Exceptions\Item\ItemIncorrectTierException;

class ItemValidator
{
    public static function notEmptyName(string $keyName, ?array $array): void {
        if(!array_key_exists($keyName, $array)) throw new ItemEmptyNameException();
    }

    public static function correctTier(int $tier): void {
        if($tier <= 0 || $tier > 8) throw new ItemIncorrectTierException();
    }

    public static function correctEnchant(int $ecnhant): void {
        if($ecnhant > 4) throw new ItemIncorrectEnchantException();
    }

    public static function notEmptyCountOfItems(?int $countOfItems): void {
        if(!$countOfItems) throw new CountOfItemsEmptyException();
    }

    public static function correctCountOfItems(int $countOfItems): void {
        if($countOfItems <= 0) throw new CountOfItemsIncorrectException();
    }
}
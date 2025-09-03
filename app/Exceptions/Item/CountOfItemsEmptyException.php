<?php

namespace App\Exceptions\Item;

class CountOfItemsEmptyException extends \Exception
{
    protected $message = "Не указано количество предметов.";
}
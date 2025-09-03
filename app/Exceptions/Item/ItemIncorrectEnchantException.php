<?php

namespace App\Exceptions\Item;

class ItemIncorrectEnchantException extends \Exception
{
    protected $message = "Некорректный уровень зачарования.";
}
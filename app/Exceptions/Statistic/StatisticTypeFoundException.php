<?php

namespace App\Exceptions\Statistic;

class StatisticTypeFoundException extends \Exception
{
    protected $message = "Тип статистики не найден.";
}
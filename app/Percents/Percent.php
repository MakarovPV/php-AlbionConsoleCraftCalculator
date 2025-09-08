<?php

namespace App\Percents;

abstract class Percent
{
    protected static array $percents;

    public static function all(): array
    {
        return static::$percents;
    }
}
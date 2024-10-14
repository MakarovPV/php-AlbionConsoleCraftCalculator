<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Craft;

$craft = new Craft(array_slice($argv, 1));
echo $craft->craft();
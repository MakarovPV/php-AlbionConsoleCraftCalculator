<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Craft;

set_exception_handler(function(Throwable $e) {
    fwrite(STDERR, "Произошла ошибка: " . $e->getMessage() . PHP_EOL);
    exit(1);
});

set_error_handler(function($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

$craft = new Craft(array_slice($argv, 1));
echo $craft->craft();
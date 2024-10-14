<?php

use App\Utils\JsonDecoder;
use Database\ElasticSearch\Items;

$elastic = new Items();
$elastic->insertAllDocuments(JsonDecoder::getDataFromJson('data/items.json'));

echo 'Данные по предметам загружены';
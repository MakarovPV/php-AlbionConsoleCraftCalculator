<?php

namespace Commands;

use App\Utils\JsonDecoder;
use Database\ElasticSearch\Cities;

$elastic = new Cities();
$elastic->insertAllDocuments(JsonDecoder::getDataFromJson('data/cities.json'));

echo 'Данные по городам загружены';
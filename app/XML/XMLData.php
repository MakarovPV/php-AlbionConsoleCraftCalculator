<?php

namespace App\XML;

abstract class XMLData
{
    protected array $generatedData = [];

    protected $xml;

    public function __construct(string $xmlFilePath)
    {
        $this->xml = simplexml_load_file($xmlFilePath);
    }

    /**
     * Заполнение массива данными из xml-файла.
     * @param string $searchValue
     * @param int $count
     * @return mixed
     */
    abstract public function generateItems(string $searchValue, int $count);

    /**
     * Получение массива со сгенерированными данными.
     * @return array
     */
    public function getGeneratedData(): array
    {
        return $this->generatedData;
    }
}
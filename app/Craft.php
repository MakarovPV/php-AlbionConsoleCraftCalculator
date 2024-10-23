<?php

namespace App;

use App\Statistics\Statistic;
use App\XML\XMLData;
use App\XML\XMLDataItemList;
use Database\ElasticSearch\Items;

class Craft
{
    private string $countOfItems;
    private array $itemNamesFromInput;
    private array $itemNamesFromElastic;
    private string $itemTier;
    private string $statisticType;
    private string $cityName;
    private Statistic $statistic;

    /**
     * На вход получаем строку, введенную пользователем в консоль.
     * @param array $arrayFromConsole
     */
    public function __construct(array $arrayFromConsole)
    {
        $parameters = new Preparation($arrayFromConsole);

        $this->countOfItems = $parameters->countOfItems;
        $this->itemNamesFromInput = $parameters->itemNamesFromInput;
        $this->itemTier = $parameters->itemTier;
        $this->statisticType = $parameters->statisticType;
        $this->cityName = $parameters->cityName;
    }

    /**
     * Запуск производственной цепочки с последующим выводом данных в консоль.
     * @return string
     */
    public function craft(): string
    {
        return $this->getStatistic();
    }

    /**
     * Сначала заполняем класс выбранного типа статистики данными, затем выводим данные конкретно по искомому предмету, затем - все данные по его комплектующим.
     * @return string
     */
    private function getStatistic(): string
    {
        $this->statistic = new $this->statisticType($this->getGeneratedItems(new XMLDataItemList()), $this->cityName);
        $this->statistic->dataOfMainItem($this->itemNamesFromElastic);
        return $this->statistic->build();
    }

    /**
     * Генерация данных по комплектующим предметам из xml-файла.
     * @param XMLData $items
     * @return array
     */
    private function getGeneratedItems(XMLData $items): array
    {
        $this->itemNamesFromElastic = $this->getItemNamesFromElastic(new Items());
        $items->generateItems($this->itemNamesFromElastic['uniqueName'], $this->countOfItems);
        return $items->getGeneratedData();
    }

    /**
     * Получение массива с полными именами предмета (на русском и английском) по полученным названию и уровню предмета из консоли.
     * @param Items $albionItems
     * @return array|null
     */
    private function getItemNamesFromElastic(Items $albionItems): array|null
    {
        $result = $albionItems->search(
            [
                'itemNames' => $this->itemNamesFromInput,
                'tier' => $this->itemTier
            ]);

        return $result;
    }
}
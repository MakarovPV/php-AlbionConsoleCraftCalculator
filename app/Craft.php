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
    private array $cityName;
    private Statistic $statistic;
    private XMLDataItemList $xml;

    /**
     * На вход получаем строку, введенную пользователем в консоль.
     * @param array $arrayFromConsole
     */
    public function __construct(array $arrayFromConsole)
    {
        $parameters = new Preparation($arrayFromConsole);
        $this->xml = new XMLDataItemList();

        $this->countOfItems = $parameters->countOfItems;
        $this->itemNamesFromInput = $parameters->itemNamesFromInput;
        $this->itemTier = $parameters->itemTier;
        $this->statisticType = $parameters->statisticType;
        $this->cityName = $parameters->cityName;

        $this->itemNamesFromElastic = $this->getItemNamesFromElastic(new Items());
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
        $this->statistic = new $this->statisticType($this->cityName);
        return $this->statistic->build($this->countOfItems,
            $this->xml->getAmountCraftedItems($this->itemNamesFromElastic['uniqueName']),
            $this->itemNamesFromElastic);
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
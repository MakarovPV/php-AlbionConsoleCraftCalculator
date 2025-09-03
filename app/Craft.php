<?php

namespace App;

use App\DTO\Items\ItemSearchDTO;
use App\DTO\Statistics\StatisticBuildDTO;
use App\Statistics\Statistic;
use App\XML\XMLDataItemList;
use Database\ElasticSearch\Items;

class Craft
{
    private string $countOfItems;
    private array $itemNamesFromInput;
    private string $itemTier;
    private string $itemEnchant;
    private string $statisticType;
    private array $cityNames;

    /**
     * Массив с полными именами предмета (на русском и английском) по полученным названию и уровню предмета из консоли.
     * @var array|null
     */
    private array $itemNamesFromElastic;

    private Statistic $statistic;
    private XMLDataItemList $xml;
    private Items $items;

    /**
     * На вход получаем строку, введенную пользователем в консоль.
     * @param array $arrayFromConsole
     */
    public function __construct(array $arrayFromConsole)
    {
        $parameters = new Preparation($arrayFromConsole);
        $this->xml = new XMLDataItemList();
        $this->items = new Items();

        $this->countOfItems = $parameters->countOfItems;
        $this->itemNamesFromInput = $parameters->itemNamesFromInput;
        $this->itemTier = $parameters->itemTier;
        $this->itemEnchant = $parameters->itemEnchant;
        $this->statisticType = $parameters->statisticType;
        $this->cityNames = $parameters->cityNames;

        $this->itemNamesFromElastic = $this->items->search(new ItemSearchDTO($this->itemNamesFromInput, $this->itemTier, $this->itemEnchant));
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
        $dataForStatistic = new StatisticBuildDTO($this->countOfItems,
            $this->xml->getAmountCraftedItems($this->itemNamesFromElastic['uniqueName']),
            $this->itemNamesFromElastic);

        $this->statistic = new $this->statisticType($this->cityNames);
        return $this->statistic->build($dataForStatistic);
    }
}
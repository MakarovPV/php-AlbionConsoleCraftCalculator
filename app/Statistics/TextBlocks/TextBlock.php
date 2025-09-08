<?php

namespace App\Statistics\TextBlocks;

use App\Traits\Calculate;

abstract class TextBlock
{
    use Calculate;

    protected array $data;
    protected string $text = '';

    abstract public function totalCost(array $data): void;

    public function getText(): string
    {
        return $this->text;
    }

    public function addText(string $text): void
    {
        $this->text .= $text;
    }

    public function addStringDelimiter(): void
    {
        $this->addText('____________________________________');
    }
}
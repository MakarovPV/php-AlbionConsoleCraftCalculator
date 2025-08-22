<?php

namespace App\Commands;

/**
 * Класс для автоматизации запуска команд.
 */
class CommandManager
{
    private array $commands;

    public function __construct(array $commandsArray)
    {
        $this->commands = $commandsArray;
    }

    public function addCommand(CommandInterface $command)
    {
        $this->commands[] = $command;
    }

    public function run()
    {
        foreach($this->commands as $command) {
            $command->run();
            echo $command->notification();
        }
    }
}
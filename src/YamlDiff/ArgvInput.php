<?php

namespace YamlDiff;

use Symfony\Component\Console\Input\ArgvInput as BaseArgvInput;

class ArgvInput extends BaseArgvInput
{
    public function __construct($commandName, InputDefinition $definition = null)
    {
        $argv = $_SERVER['argv'];

        // auto insert command name
        array_splice($argv, 1, 0, $commandName);

        parent::__construct($argv, $definition);
    }
}

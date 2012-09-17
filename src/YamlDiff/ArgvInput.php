<?php

/*
 * This file is part of YamlDiff.
 *
 * (c) Matthieu Moquet <matthieu@moquet.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YamlDiff;

use Symfony\Component\Console\Input\ArgvInput as BaseArgvInput;

/**
 * @author Matthieu Moquet <matthieu@moquet.net>
 */
class ArgvInput extends BaseArgvInput
{
    public function __construct($commandName, array $argv = null, InputDefinition $definition = null)
    {
        if (null === $argv) {
            $argv = $_SERVER['argv'];
        }

        // auto insert command name
        array_splice($argv, 1, 0, $commandName);

        parent::__construct($argv, $definition);
    }
}

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

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Matthieu Moquet <matthieu@moquet.net>
 */
class Application extends BaseApplication
{
    const VERSION = '@package_version@';

    /**
     * {@inheritdoc}
     */
    protected function getCommandName(InputInterface $input)
    {
        return 'yamldiff';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = new YamlDiffCommand();

        return $defaultCommands;
    }
}
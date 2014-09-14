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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Yaml\Parser;

/**
 * Make diff between two Yaml files.
 *
 * @author Matthieu Moquet <matthieu@moquet.net>
 */
class YamlDiffCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('yamldiff')
            ->setDescription('Compare keys between two Yaml files')
            ->addArgument('file1', InputArgument::REQUIRED, 'Reference YAML file')
            ->addArgument('file2', InputArgument::REQUIRED, 'Comparison YAML file')
            ->addOption('ignore-extra', null, InputOption::VALUE_NONE, 'Ignore keys present on file2 and missing on file1.')
            ->addOption('ignore-missing', null, InputOption::VALUE_NONE, 'Ignore keys present on file1 and missing on file2.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->getFormatter()->setStyle('add', new OutputFormatterStyle('green'));
        $output->getFormatter()->setStyle('del', new OutputFormatterStyle('red'));

        $file1 = $input->getArgument('file1');
        $file2 = $input->getArgument('file2');

        if (!file_exists($file1)) {
            throw new \InvalidArgumentException(sprintf('File %s doesn\'t exist', $file1));
        }
        if (!file_exists($file2)) {
            throw new \InvalidArgumentException(sprintf('File %s doesn\'t exist', $file2));
        }

        $yaml = new Parser();
        $values1 = (array) $yaml->parse(file_get_contents($file1));
        $values2 = (array) $yaml->parse(file_get_contents($file2));

        $values1 = $this->flattenArray($values1);
        $values2 = $this->flattenArray($values2);

        $diff1 = array_diff_key($values1, $values2);
        $diff2 = array_diff_key($values2, $values1);

        $returnCode = 0;

        if (!$input->getOption('ignore-missing')) {
            if (!empty($diff1)) {
                $returnCode = 1;
            }

            foreach ($diff1 as $key => $value) {
                $output->writeln(sprintf('<add>+%s</add>', $key));
            }
        }

        if (!$input->getOption('ignore-extra')) {
            if (!empty($diff2)) {
                $returnCode = 1;
            }

            foreach ($diff2 as $key => $value) {
                $output->writeln(sprintf('<del>-%s</del>', $key));
            }
        }

        return $returnCode;
    }

    /**
     * @param array  $values
     * @param string $prefix
     *
     * @return array
     */
    protected function flattenArray(array $values, $prefix = null)
    {
        $messages = array();
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $messages[$prefix] = $values;
                continue;
            }

            if (null !== $prefix) {
                $key = $prefix.'.'.$key;
            }

            if (is_array($value)) {
                $messages += $this->flattenArray($value, $key);
                continue;
            }

            $messages[$key] = $value;
        }

        return $messages;
    }
}

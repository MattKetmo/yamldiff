<?php

namespace YamlDiff;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Yaml\Parser;

/**
 * Make diff between two Yaml files
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
            ->addArgument('file1', InputArgument::REQUIRED)
            ->addArgument('file2', InputArgument::REQUIRED)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->getFormatter()->setStyle('add', new OutputFormatterStyle('green'));
        $output->getFormatter()->setStyle('del', new OutputFormatterStyle('red'));

        $yaml = new Parser();

        $values1 = $yaml->parse(file_get_contents($input->getArgument('file1')));
        $values2 = $yaml->parse(file_get_contents($input->getArgument('file2')));

        $values1 = $this->flattenArray($values1);
        $values2 = $this->flattenArray($values2);

        $diff1 = array_diff_key($values1, $values2);
        $diff2 = array_diff_key($values2, $values1);

        foreach ($diff1 as $key => $value) {
            $output->writeln(sprintf('<add>+%s</add>', $key));
        }

        foreach ($diff2 as $key => $value) {
            $output->writeln(sprintf('<del>-%s</del>', $key));
        }
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

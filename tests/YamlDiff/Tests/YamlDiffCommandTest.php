<?php

/*
 * This file is part of YamlDiff.
 *
 * (c) Matthieu Moquet <matthieu@moquet.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YamlDiff\Tests;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;

class YamlDiffCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testYamlDiff_SameFile()
    {
        $output = $this->runCommand(__DIR__.'/fixtures/file1.yml', __DIR__.'/fixtures/file1.yml');
        $this->assertEmpty($output);
    }

    public function testYamlDiff_MissingParameter()
    {
        $output = $this->runCommand(__DIR__.'/fixtures/file1.yml', __DIR__.'/fixtures/file2.yml');
        $this->assertContains('+parameters.chuck', $output);
        $this->assertContains('-parameters.john', $output);
    }

    public function testYamlDiff_ItemCountDoesntMatter()
    {
        $output = $this->runCommand(__DIR__.'/fixtures/file2.yml', __DIR__.'/fixtures/file3.yml');
        $this->assertEmpty($output);
    }

    /**
     * Runs the command and returns it output
     */
    protected function runCommand($file1, $file2)
    {
        $application = new Application('YamlDiff');
        $application->add(new \YamlDiff\YamlDiffCommand);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'yamldiff',
            'file1'   => $file1,
            'file2'   => $file2
        ));

        $fp = tmpfile();
        $output = new StreamOutput($fp);

        $application->run($input, $output);

        fseek($fp, 0);
        $output = '';
        while (!feof($fp)) {
            $output = fread($fp, 4096);
        }
        fclose($fp);

        return $output;
    }
}

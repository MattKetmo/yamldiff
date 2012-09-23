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
        $returnCode = $this->runCommand('file1.yml', 'file1.yml', $output);
        $this->assertEmpty($output);
        $this->assertEquals(0, $returnCode);
    }

    public function testYamlDiff_MissingParameter()
    {
        $returnCode = $this->runCommand('file1.yml', 'file2.yml', $output);
        $this->assertContains('+parameters.chuck', $output);
        $this->assertContains('-parameters.john', $output);
        $this->assertEquals(1, $returnCode);
    }

    public function testYamlDiff_ItemCountDoesntMatter()
    {
        $returnCode = $this->runCommand('file2.yml', 'file3.yml', $output);
        $this->assertEmpty($output);
        $this->assertEquals(0, $returnCode);
    }

    /**
     * Runs the command and returns it output
     */
    protected function runCommand($file1, $file2, &$output = null)
    {
        $application = new Application('YamlDiff');
        $application->add(new \YamlDiff\YamlDiffCommand);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'yamldiff',
            'file1'   => __DIR__.'/fixtures/'.$file1,
            'file2'   => __DIR__.'/fixtures/'.$file2
        ));

        $fp = tmpfile();
        $output = new StreamOutput($fp);

        $returnCode = $application->run($input, $output);

        fseek($fp, 0);
        $output = '';
        while (!feof($fp)) {
            $output = fread($fp, 4096);
        }
        fclose($fp);

        return $returnCode;
    }
}

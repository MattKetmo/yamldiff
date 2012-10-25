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
        $returnCode = $this->runCommand('file1.yml', 'file1.yml', array(), $output);
        $this->assertEmpty($output, 'There is no output');
        $this->assertEquals(0, $returnCode, 'Success exit code');
    }

    public function testYamlDiff_MissingParameter()
    {
        $returnCode = $this->runCommand('file1.yml', 'file2.yml', array(), $output);
        $this->assertContains('+parameters.chuck', $output, 'The key "parameters.chuck" is missing in file2');
        $this->assertContains('-parameters.john', $output, 'The key "parameters.john" is missing in file1');
        $this->assertEquals(1, $returnCode, 'Error exit code');
    }

    public function testYamlDiff_IgnoreExtra()
    {
        $returnCode = $this->runCommand('file1.yml', 'file2.yml', array('--ignore-extra' => true), $output);
        $this->assertContains('+parameters.chuck', $output, 'The key "parameters.chuck" is missing');
        $this->assertNotContains('-parameters.john', $output, 'The command do not show the key missing in file1');
        $this->assertEquals(1, $returnCode, 'Error exit code');
    }

    public function testYamlDiff_IgnoreMissing()
    {
        $returnCode = $this->runCommand('file1.yml', 'file2.yml', array('--ignore-missing' => true), $output);
        $this->assertNotContains('+parameters.chuck', $output, 'The command do not show the key missing in file2');
        $this->assertContains('-parameters.john', $output, 'The key "parameters.john" is missing in file1');
        $this->assertEquals(1, $returnCode, 'Error exit code');
    }

    public function testYamlDiff_ItemCountDoesntMatter()
    {
        $returnCode = $this->runCommand('file2.yml', 'file3.yml', array(), $output);
        $this->assertEmpty($output, 'There is no output');
        $this->assertEquals(0, $returnCode, 'Success exit code');
    }

    public function testYamlDiff_QuietOutput()
    {
        $returnCode = $this->runCommand('file1.yml', 'file2.yml', array('--quiet' => true), $output);
        $this->assertEmpty($output, 'There is no output');
        $this->assertEquals(1, $returnCode, 'Error exit code');
    }

    /**
     * Runs the command and returns it exit code
     *
     * @param string $file1  First yaml file path
     * @param string $file2  Second yaml file path
     * @param string $params Extra parameters (to add options)
     * @param string $output The output of the command by reference
     *
     * @return int Exit code
     */
    protected function runCommand($file1, $file2, $params = array(), &$output = null)
    {
        $application = new Application('YamlDiff');
        $application->add(new \YamlDiff\YamlDiffCommand);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'yamldiff',
            'file1'   => __DIR__.'/fixtures/'.$file1,
            'file2'   => __DIR__.'/fixtures/'.$file2
        ) + $params);

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

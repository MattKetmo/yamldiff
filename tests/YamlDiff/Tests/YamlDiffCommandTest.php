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

use YamlDiff\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

class YamlDiffCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ApplicationTester
     */
    protected $tester;

    public function setUp()
    {
        $application = new Application('YamlDiff');
        $application->setAutoExit(false);

        $this->tester = new ApplicationTester($application);
    }

    public function testSameFile()
    {
        $returnCode = $this->runCommand('file1.yml', 'file1.yml');
        $output = $this->tester->getDisplay();

        $this->assertEmpty($output, 'There is no output');
        $this->assertEquals(0, $returnCode, 'Success exit code');
    }

    public function testMissingParameter()
    {
        $returnCode = $this->runCommand('file1.yml', 'file2.yml');
        $output = $this->tester->getDisplay();

        $this->assertContains('+parameters.chuck', $output, 'The key "parameters.chuck" is missing in file2');
        $this->assertContains('-parameters.john', $output, 'The key "parameters.john" is missing in file1');
        $this->assertEquals(1, $returnCode, 'Error exit code');
    }

    public function testIgnoreExtra()
    {
        $returnCode = $this->runCommand('file1.yml', 'file2.yml', array('--ignore-extra' => true));
        $output = $this->tester->getDisplay();

        $this->assertContains('+parameters.chuck', $output, 'The key "parameters.chuck" is missing');
        $this->assertNotContains('-parameters.john', $output, 'The command do not show the key missing in file1');
        $this->assertEquals(1, $returnCode, 'Error exit code');
    }

    public function testIgnoreMissing()
    {
        $returnCode = $this->runCommand('file1.yml', 'file2.yml', array('--ignore-missing' => true));
        $output = $this->tester->getDisplay();

        $this->assertNotContains('+parameters.chuck', $output, 'The command do not show the key missing in file2');
        $this->assertContains('-parameters.john', $output, 'The key "parameters.john" is missing in file1');
        $this->assertEquals(1, $returnCode, 'Error exit code');
    }

    public function testItemCountDoesntMatter()
    {
        $returnCode = $this->runCommand('file2.yml', 'file3.yml');
        $output = $this->tester->getDisplay();

        $this->assertEmpty($output, 'There is no output');
        $this->assertEquals(0, $returnCode, 'Success exit code');
    }

    public function testQuietOutput()
    {
        $returnCode = $this->runCommand('file1.yml', 'file2.yml', array('--quiet' => true));
        $output = $this->tester->getDisplay();

        $this->assertEmpty($output, 'There is no output');
        $this->assertEquals(1, $returnCode, 'Error exit code');
    }

    /**
     * Runs the command and returns it exit code
     *
     * @param string $file1  First yaml file path
     * @param string $file2  Second yaml file path
     * @param string $params Extra parameters (to add options)
     *
     * @return int Exit code
     */
    protected function runCommand($file1, $file2, array $params = array())
    {
        $input = array(
            'command' => 'yamldiff',
            'file1'   => __DIR__.'/fixtures/'.$file1,
            'file2'   => __DIR__.'/fixtures/'.$file2
        ) + $params;

        return $this->tester->run($input);
    }
}

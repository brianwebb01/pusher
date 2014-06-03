<?php namespace Configuration;

use Console\OptionReader;
use Mockery as m;

class OptionReaderTest extends \PusherTest
{

    public function testHasConfigPath()
    {
        $reader = new OptionReader([
                'config' => null
            ]);
        $this->assertEquals(false, $reader->hasConfigPath());

        $reader = new OptionReader([
                'config' => 'path/to/file'
            ]);
        $this->assertEquals(true, $reader->hasConfigPath());
    }



    public function testConfigPath()
    {
        $reader = new OptionReader([]);
        $this->assertEquals(getcwd().DIRECTORY_SEPARATOR.ConfigReader::FILENAME, $reader->configPath());

        $path = getcwd().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.ConfigReader::FILENAME;
        $reader = new OptionReader([
                'config' => $path
            ]);
        $this->assertEquals(realpath($path), $reader->configPath());
    }
}
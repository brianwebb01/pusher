<?php namespace Configuration;

use Mockery as m;
use Seld\JsonLint\ParsingException;

class ConfigLoaderTest extends \PusherTest
{

    public function testShouldLoadAndOverwriteDefault()
    {
        $pathToConfig = '/path/to/config';
        $optionReader = m::mock('Console\OptionReader');
        $optionReader
            ->shouldReceive('configPath')
            ->once()
            ->andReturn($pathToConfig);

        $parser = m::mock('Seld\JsonLint\JsonParser');
        $config = [
            'defaultEnvironment' => 'production'
        ];
        $parser
            ->shouldReceive('parse')
            ->with($pathToConfig)
            ->once()
            ->andReturn($config);

        $loader = new ConfigLoader($parser);

        $this->assertEquals($config, $loader->load($optionReader));
    }

    public function testShouldNotLoad()
    {
        $this->setExpectedException('Parse\ParsingException');

        $parser = m::mock('Seld\JsonLint\JsonParser');
        $parser
            ->shouldReceive('parse')
            ->once()
            ->andThrow(new ParsingException(''));

        $loader = new ConfigLoader($parser);
        $loader->load(m::mock('Console\OptionReader')->shouldIgnoreMissing());
    }

    public function testDefaultConfigPath()
    {
        $parser = m::mock('Seld\JsonLint\JsonParser');
        $loader = new ConfigLoader($parser);

        $this->assertContains('src/lib/Configuration/../deployment.json', $loader->defaultConfigPath());
    }
}
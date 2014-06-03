<?php namespace Configuration;

use Mockery as m;
use Seld\JsonLint\ParsingException;
use Configuration\Environment;

class EnvironmentTest extends \PusherTest
{

    public function testGetters()
    {
        $name = 'dev';
        $branch = 'develop';
        $user = 'deploy';
        $servers = [
            'dev1', 'dev2'
        ];
        $environment = new Environment($name, $branch, $user, $servers);

        $this->assertEquals($name, $environment->name());
        $this->assertEquals($branch, $environment->branch());
        $this->assertEquals($user, $environment->user());
        $this->assertEquals($servers, $environment->servers());
    }

}
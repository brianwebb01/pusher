<?php

use Mockery as m;

abstract class PusherTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }
}

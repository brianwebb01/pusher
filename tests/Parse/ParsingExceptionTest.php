<?php namespace Configuration;

use Console\OptionReader;
use Mockery as m;
use Parse\ParsingException;

class ParsingExceptionTest extends \PusherTest
{

    public function testDetails()
    {
        $details = [
            'message' => 'failed on line no 5'
        ];
        $exception = new ParsingException();
        $exception->setDetails($details);

        $this->assertEquals($details, $exception->getDetails());
    }
}
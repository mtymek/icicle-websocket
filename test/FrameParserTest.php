<?php

namespace BlastTest;

use Blast\Frame;
use Blast\FrameParser;
use Generator;
use Icicle\Socket\Client\ClientInterface;
use PHPUnit_Framework_TestCase;

class FrameParserTest extends PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $parser = new FrameParser();
        $client = $this->prophesize(ClientInterface::class);
        $ret = null;
        $sampleFrame = "\x81\x87\x81\x9b\xca\xbd\xe0\xf9\xa9\xd9\xe0\xf9\xa9";
        $i = 0;
        while (!$ret instanceof Frame) {
            $ret = $parser->readFrame($client->reveal());
            if ($ret instanceof Generator) {
                echo bin2hex($sampleFrame[$i]);
                $ret->send($sampleFrame[$i]);
                $i++;
            }
        };
    }
}

<?php

namespace BlastTest;

use Blast\Frame;
use Blast\FrameReader;
use Icicle\Coroutine\Coroutine;
use Icicle\Stream\Stream;
use Icicle\Loop;
use PHPUnit_Framework_TestCase;

class FrameParserTest extends PHPUnit_Framework_TestCase
{
    public function testParse()
    {
        $sampleFrame = "\x81\x87\x81\x9b\xca\xbd\xe0\xf9\xa9\xd9\xe0\xf9\xa9";
        $stream = new Stream();
        $stream->end($sampleFrame);

        $parser = new FrameReader();
        $promise = new Coroutine($parser->read($stream));

        $promise->done(function (Frame $frame) {
            $this->assertEquals(1, $frame->getFin());
            $this->assertEquals(Frame::OPCODE_TEXT, $frame->getOpcode());
            $this->assertEquals(1, $frame->getMask());
            $this->assertEquals("\x81\x9b\xca\xbd", $frame->getMaskingKey());
            $this->assertEquals('abcdabc', $frame->getData());
        });

        Loop\run();
    }
}

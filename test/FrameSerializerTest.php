<?php

namespace BlastTest;

use Blast\Frame;
use Blast\FrameSerializer;
use Icicle\Loop;
use PHPUnit_Framework_TestCase;

class FrameSerializerTest extends PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $frame = new Frame(1, Frame::OPCODE_TEXT, 1, 7, "\x81\x9b\xca\xbd", "abcdabc");
        $serializer = new FrameSerializer();
        $data = $serializer->serialize($frame);
        $this->assertEquals("\x81\x87\x81\x9b\xca\xbd\xe0\xf9\xa9\xd9\xe0\xf9\xa9", $data);
    }
}

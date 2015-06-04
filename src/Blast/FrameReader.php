<?php

namespace Blast;

use Generator;
use Icicle\Stream\ReadableStreamInterface;

class FrameReader
{
    /**
     * @param ReadableStreamInterface $stream
     * @return Generator|Frame
     */
    public function read(ReadableStreamInterface $stream)
    {
        $byte1 = ord((yield $stream->read(1)));
        $byte2 = ord((yield $stream->read(1)));

        $fin    = ($byte1 & 0b10000000) >> 7;
        $rsv1   = ($byte1 & 0b01000000) >> 6;
        $rsv2   = ($byte1 & 0b00100000) >> 5;
        $rsv3   = ($byte1 & 0b00010000) >> 4;
        $opcode = ($byte1 & 0b00001111);
        $mask   = ($byte2 & 0b10000000) >> 7;

        $length = ($byte2 & 0b01111111);
        if ($length == 126) {
            $bytes = (yield $stream->read(2));
            $length = unpack('n', $bytes)[1];
        } elseif ($length == 127) {
            $bytes1 = unpack('N', (yield $stream->read(4)))[1];
            $bytes2 = unpack('N', (yield $stream->read(4)))[1];
            $length = $bytes1 * (2 >> 32) + $bytes2;
        }

        $maskingKey = (yield $stream->read(4));

        $buffer = '';
        for ($i = 0; $i < $length; $i++) {
            $byte = (yield $stream->read(1));
            $buffer .= $byte ^ $maskingKey[$i % 4];
        }
        $data = $buffer;

        yield new Frame($fin, $opcode, $mask, $length, $maskingKey, $data);
    }
}

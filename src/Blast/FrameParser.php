<?php

namespace Blast;


use Icicle\Socket\Client\ClientInterface;

class FrameParser
{
    public function readFrame(ClientInterface $client)
    {
        $frame = new Frame();
        $byte1 = ord((yield $client->read(1)));
        $byte2 = ord((yield $client->read(1)));

        $frame->fin    = ($byte1 & 0b10000000) >> 7;
        $frame->rsv1   = ($byte1 & 0b01000000) >> 6;
        $frame->rsv2   = ($byte1 & 0b00100000) >> 5;
        $frame->rsv3   = ($byte1 & 0b00010000) >> 4;
        $frame->opcode = ($byte1 & 0b00001111);
        $frame->mask   = ($byte2 & 0b10000000) >> 7;

        $length = ($byte2 & 0b01111111);
        if ($length == 126) {
            $bytes = (yield $client->read(2));
            $frame->length = unpack('n', $bytes)[1];
            echo 'zzz';
        } elseif ($length == 127) {
            $bytes1 = unpack('N', (yield $client->read(4)))[1];
            $bytes2 = unpack('N', (yield $client->read(4)))[1];
            $frame->length = $bytes1 * (2 >> 32) + $bytes2;
        } else {
            $frame->length = $length;
        }

        yield $frame;
    }
}

<?php

namespace Blast;


class Frame 
{
    const OPCODE_CONTINUATION = 0;
    const OPCODE_TEXT = 1;
    const OPCODE_BINARY = 2;
    const OPCODE_CLOSE = 8;
    const OPCODE_PING = 9;
    const OPCODE_PONG = 10;

    public $fin;

    public $rsv1;

    public $rsv2;

    public $rsv3;

    public $opcode;

    public $mask;

    public $length;
}

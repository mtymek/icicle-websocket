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

    /**
     * @var int
     */
    private $fin;

    /**
     * @var int
     */
    private $rsv1;

    /**
     * @var int
     */
    private $rsv2;

    /**
     * @var int
     */
    private $rsv3;

    /**
     * @var int
     */
    private $opcode;

    /**
     * @var string
     */
    private $mask;

    /**
     * @var int
     */
    private $length;

    /**
     * @var string
     */
    private $maskingKey;

    /**
     * @var string
     */
    private $data;

    /**
     * @param $fin
     * @param $opcode
     * @param $mask
     * @param $length
     * @param $maskingKey
     * @param $data
     */
    public function __construct($fin, $opcode, $mask, $length, $maskingKey, $data)
    {
        $this->fin = $fin;
        $this->opcode = $opcode;
        $this->mask = $mask;
        $this->length = $length;
        $this->maskingKey = $maskingKey;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * @return mixed
     */
    public function getRsv1()
    {
        return $this->rsv1;
    }

    /**
     * @return mixed
     */
    public function getRsv2()
    {
        return $this->rsv2;
    }

    /**
     * @return mixed
     */
    public function getRsv3()
    {
        return $this->rsv3;
    }

    /**
     * @return mixed
     */
    public function getOpcode()
    {
        return $this->opcode;
    }

    /**
     * @return mixed
     */
    public function getMask()
    {
        return $this->mask;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return mixed
     */
    public function getMaskingKey()
    {
        return $this->maskingKey;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }


}

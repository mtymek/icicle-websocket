<?php

namespace Blast;

class FrameSerializer
{
    private function mask($data, $mask)
    {
        $masked = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $masked .= $data[$i] ^ $mask[$i % 4];
        }
        return $masked;
    }

    public function serialize(Frame $frame)
    {
        // first byte: fin
        $serialized = chr(($frame->getFin() << 7) + $frame->getOpcode());

        $mask = $frame->getMask();

        // second byte: mask & length
        if (strlen($frame->getData()) < 126) {
            $serialized .= chr(($mask << 7) + $frame->getLength());
        } elseif (strlen($frame) < pow(2, 16)) {
            // TODO:
            $serialized .= chr($mask);
        } else {
            // TODO:
            $serialized .= chr($mask);
        }
        if ($mask) {
            $serialized .= $frame->getMaskingKey();
            $serialized .= $this->mask($frame->getData(), $frame->getMaskingKey());
        } else {
            $serialized .= $frame->getData();
        }

        return $serialized;
    }
}

<?php

namespace Blast;

class WebSocketEcho
{
    public function __invoke(WebSocketConnection $connection, Frame $frame)
    {
        yield $connection->send(
            sprintf(
                "TO %s:%s: ECHO: %s",
                $connection->getClient()->getRemoteAddress(),
                $connection->getClient()->getRemotePort(),
                $frame->getData()
            )
        );
    }
}

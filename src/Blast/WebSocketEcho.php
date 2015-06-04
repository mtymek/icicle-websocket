<?php

namespace Blast;

class WebSocketEcho
{
    public function __invoke(WebSocketConnection $connection, Frame $frame)
    {
        yield $connection->send(
            sprintf(
                "TO %s: ECHO: %s",
                $connection->getClient()->getLocalAddress(),
                $frame->getData()
            )
        );
    }
}

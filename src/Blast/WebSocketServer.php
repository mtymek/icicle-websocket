<?php

namespace Blast;

use Icicle\Coroutine\Coroutine;
use Icicle\Http\Message\RequestInterface;
use Icicle\Http\Message\Response;
use Icicle\Http\Server\Server;
use Icicle\Socket\Client\ClientInterface;

class WebSocketServer extends Server
{
    /**
     * @var callable
     */
    private $onMessage;

    public function __construct(callable $onMessage = null)
    {
        parent::__construct(
            [$this, 'handshake']
        );
//        parent::__construct(
//            [$this, 'handshake'],
//            null,
//            function (ClientInterface $client) {}
//        );
        $this->onMessage = $onMessage;
    }

    public function handshake(RequestInterface $request, ClientInterface $client)
    {
        if ($request->getHeaderLine('Connection') != 'Upgrade') {
            $response = new Response(426);
            return $response;
        }

        if (strtolower($request->getHeaderLine('Upgrade') != 'websocket')
            || !$request->getHeaderLine('Sec-WebSocket-Key')
        ) {
            $response = new Response(400);
            return $response;
        }

        $response = new Response(101);
        $response = $response->withHeader('Upgrade', 'websocket')
            ->withHeader('Connection', 'Upgrade')
            ->withHeader(
                "Sec-WebSocket-Accept",
                base64_encode(
                    sha1(
                        $request->getHeaderLine('Sec-WebSocket-Key')
                        . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11",
                        true
                    )
                )
            );

        $connection = new WebSocketConnection($request, $client);

        (new Coroutine($this->handleWsRequest($connection)))->done();

        return $response;
    }

    public function handleWsRequest(WebSocketConnection $connection)
    {
        $parser = new FrameReader();
        $frame = (yield $parser->read($connection->getClient()));

        if ($this->onMessage) {
            yield $this->onMessage($connection, $frame);
        }
    }
}

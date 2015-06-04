<?php

namespace Blast;

use Icicle\Http\Message\RequestInterface;
use Icicle\Http\Message\Response;
use Icicle\Http\Server\Server;
use Icicle\Socket\Client\ClientInterface;

class WebSocketServer extends Server
{
    public function __construct()
    {
        parent::__construct(
            [$this, 'handshake'],
            null,
            [$this, 'handleWsRequest']
        );
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

        return $response;
    }

    public function handleWsRequest(ClientInterface $client)
    {
        $parser = new FrameReader();
        $frame = (yield $parser->read($client));
        print_r($frame);
        echo bin2hex($frame->getMaskingKey());
    }
}

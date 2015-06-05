<?php

namespace Blast;

use Icicle\Http\Message\RequestInterface;
use Icicle\Http\Message\ResponseInterface;
use Icicle\Socket\Client\ClientInterface;

class WebSocketConnection
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(RequestInterface $request, ResponseInterface $response, ClientInterface $client)
    {
        $this->client = $client;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function send($data)
    {
        $frame = new Frame(1, Frame::OPCODE_TEXT, 0, strlen($data), null, $data);
        $serializer = new FrameSerializer();
        yield $this->client->write($serializer->serialize($frame));
    }
}

<?php

namespace Blast;

use Icicle\Http\Message\RequestInterface;
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

    public function __construct(RequestInterface $request, ClientInterface $client)
    {
        $this->client = $client;
        $this->request = $request;
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
}

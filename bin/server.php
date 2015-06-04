<?php

use Blast\WebSocketEcho;
use Blast\WebSocketServer;
use Icicle\Loop;

chdir(dirname(__DIR__));

include 'vendor/autoload.php';

$server = new WebSocketServer(new WebSocketEcho());
$server->listen(8080);

Loop\run();

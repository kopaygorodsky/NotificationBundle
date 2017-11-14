<?php

namespace Kopay\NotificationBundle\Server;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class RatchetStack implements ServerStackInterface
{
    /**
     * @var NotificationServer
     */
    protected $server;

    /**
     * @var int
     */
    private $port;

    public function __construct(NotificationServer $notificationServer, int $port)
    {
        $this->port = $port;
        $this->server = IoServer::factory(new HttpServer(
            new WsServer(
                $notificationServer
            )
        ), $port);
    }

    public function run(): void
    {
        $this->server->run();
    }

    public function getHost(): string
    {
        return '0.0.0.0'; //default host
    }

    public function getPort(): int
    {
        return $this->port;
    }
}
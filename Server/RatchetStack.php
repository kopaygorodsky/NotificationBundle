<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var NotificationServer
     */
    private $notificationServer;

    public function __construct(NotificationServer $notificationServer, string $host, int $port)
    {
        $this->host   = $host;
        $this->port   = $port;
        $this->notificationServer = $notificationServer;
    }

    public function run(): void
    {
        $this->server = IoServer::factory(new HttpServer(
            new WsServer(
                $this->notificationServer
            )
        ), $this->port, $this->host);

        $this->server->run();
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }
}

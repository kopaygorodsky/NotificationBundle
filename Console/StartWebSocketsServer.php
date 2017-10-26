<?php

namespace Kopay\NotificationBundle\Console;

use Kopay\NotificationBundle\Server\Notification;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StartWebSocketsServer extends Command
{
    /**
     * @var Notification
     */
    protected $server;

    /**
     * @var int
     */
    protected $port;

    public function __construct(int $port, Notification $server)
    {
        parent::__construct();
        $this->port = $port;
        $this->server = $server;
    }

    public function configure()
    {
        $this
            ->setName('notification:server:start')
            ->setDescription('Start the notification server.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $server = IoServer::factory(new HttpServer(
            new WsServer(
                $this->server
            )
        ), $this->port);

        $output->writeln('<info>[OK] Server listening on localhost:8080 </info>');
        $server->run();
    }
}
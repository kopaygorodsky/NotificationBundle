<?php

namespace Kopay\NotificationBundle\Console;

use Kopay\NotificationBundle\Server\Notification;
use Kopay\NotificationBundle\Server\Security\AuthProviderInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StartWebSocketsServer extends Command
{
    /**
     * @var AuthProviderInterface
     */
    protected $authProvider;

    /**
     * @var int
     */
    protected $port;

    public function __construct(int $port, AuthProviderInterface $provider = null)
    {
        parent::__construct();
        $this->port = $port;
        $this->authProvider = $provider;
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
                new Notification()
            ),
            $this->authProvider
        ), $this->port);

        $output->writeln('<info>[OK] Server listening on localhost:8080 </info>');
        $server->run();
    }
}
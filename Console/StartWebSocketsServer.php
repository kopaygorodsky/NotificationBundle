<?php

namespace Kopay\NotificationBundle\Console;

use Kopay\NotificationBundle\Server\ServerStackInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StartWebSocketsServer extends Command
{
    /**
     * @var ServerStackInterface
     */
    protected $serverStack;

    public function __construct(ServerStackInterface $serverStack)
    {
        parent::__construct();
        $this->serverStack = $serverStack;
    }

    public function configure()
    {
        $this
            ->setName('notification:server:start')
            ->setDescription('Start the notification server.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<info>[OK] Server listening on ws://%s:%s </info>', $this->serverStack->getHost(), $this->serverStack->getPort()));
        $this->serverStack->run();
    }
}
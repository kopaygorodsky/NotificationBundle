<?php

namespace Kopaygorodsky\NotificationBundle\Console;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityNotFoundException;
use Kopaygorodsky\NotificationBundle\Entity\Notification;
use Kopaygorodsky\NotificationBundle\Provider\NotificationProviderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SendNotificationCommand extends Command
{
    /**
     * @var array|NotificationProviderInterface[]
     */
    private $sendingProviders;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $manager,  EventDispatcherInterface $dispatcher, array $sendingProviders)
    {
        parent::__construct();
        $this->objectManager = $manager;
        $this->eventDispatcher = $dispatcher;
        $this->sendingProviders = (function (NotificationProviderInterface ...$providers) {
            return $providers;
        })(...$sendingProviders);
    }

    public function configure()
    {
        $this
            ->setName(NotificationCommandInterface::SEND_NOTIFICATION)
            ->addArgument('notification', InputArgument::REQUIRED, 'Notification id')
            ->setDescription('Send notification')
            //->setHidden(true)
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $notification = $this->objectManager->getRepository(Notification::class)->find($notificationId = $input->getArgument('notification'));

        if (!$notification) {
            throw new EntityNotFoundException(sprintf('Notification with id %s not found', $notificationId));
        }

        foreach ($this->sendingProviders as $provider) {
            if ($provider->support($notification)) {
                $provider->send($notification);
            }
        }
        $output->writeln(sprintf('<info>Notification %s has been sent</info>', $notificationId));
    }

}
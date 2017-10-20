<?php

namespace Kopay\NotificationBundle\Console;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityNotFoundException;
use Kopay\NotificationBundle\Entity\Notification;
use Kopay\NotificationBundle\Entity\NotificationMessageInterface;
use Kopay\NotificationBundle\Event\NotificationEvent;
use Kopay\NotificationBundle\Event\NotificationEventFailed;
use Kopay\NotificationBundle\Event\NotificationEventInterface;
use Kopay\NotificationBundle\Provider\NotificationProviderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SendNotificationCommand extends Command implements NotificationCommandInterface
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
            ->setHidden(true)
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $notification = $this->getNotification($notificationId = $input->getArgument('notification'));
        $event = new NotificationEvent($notification);

        $this->eventDispatcher->dispatch(NotificationEventInterface::JOB_PRE_SEND, $event);

        try {
            foreach ($this->sendingProviders as $provider) {
                if ($provider->supports($notification)) {
                    $provider->send($notification);
                }
            }

            $this->eventDispatcher->dispatch(NotificationEventInterface::JOB_POST_SEND, $event);

            $output->writeln(sprintf('<info>Notification %s has been sent</info>', $notificationId));
        } catch (\Exception $exception) {
            $this->eventDispatcher->dispatch(NotificationEventInterface::JOB_FAILED, new NotificationEventFailed($notification, $exception));
            throw $exception;
        }
    }

    /**
     * @param $id
     * @return NotificationMessageInterface
     * @throws EntityNotFoundException
     */
    public function getNotification($id): NotificationMessageInterface
    {
        $notification = $this->objectManager->getRepository(Notification::class)->find($id);

        if (!$notification) {
            throw new EntityNotFoundException(sprintf('Notification with id %s not found', $id));
        }

        return $notification;
    }
}
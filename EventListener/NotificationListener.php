<?php

namespace Kopay\NotificationBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Kopay\NotificationBundle\Event\NotificationEventInterface;
use Kopay\NotificationBundle\Job\JobProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NotificationListener
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var JobProviderInterface
     */
    private $jobProvider;

    /**
     * @var ValidatorInterface
     */
    private $validator;


    public function __construct(ObjectManager $manager, EventDispatcherInterface $dispatcher, JobProviderInterface $jobProvider, ValidatorInterface $validator)
    {
        $this->objectManager = $manager;
        $this->dispatcher = $dispatcher;
        $this->jobProvider = $jobProvider;
        $this->validator = $validator;

    }

    public function onNotificationCreated(NotificationEventInterface $event): void
    {
        //write this notification to db, now we don't have any other options
        $notification = $event->getNotification();

        $errors = $this->validator->validate($notification);

        if ($errors->count() > 0) {
            throw new ValidatorException($errors[0]->getMessage());
        }

        $this->objectManager->persist($notification);

        $this->dispatcher->dispatch(NotificationEventInterface::NOTIFICATION_POST_PERSIST, $event);

        // create a job to make notifications async.
        $this->jobProvider->createJob($notification);

        $this->dispatcher->dispatch(NotificationEventInterface::JOB_CREATED, $event);
    }
}
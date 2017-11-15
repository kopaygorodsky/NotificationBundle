<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Kopay\NotificationBundle\Event\Events;
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
        $this->dispatcher    = $dispatcher;
        $this->jobProvider   = $jobProvider;
        $this->validator     = $validator;
    }

    public function onNotificationCreated(NotificationEventInterface $event): void
    {
        $notification = $event->getNotification();
        $errors       = $this->validator->validate($notification);

        if ($errors->count() > 0) {
            throw new ValidatorException($errors[0]->getMessage());
        }

        $this->objectManager->persist($notification);
        // create a job to send notification.
        $this->jobProvider->createJob($notification);
        $this->dispatcher->dispatch(Events::NOTIFICATION_JOB_CREATED, $event);
    }
}

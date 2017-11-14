<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Job;

use Doctrine\ORM\EntityManager;
use JMS\JobQueueBundle\Entity\Job;
use Kopay\NotificationBundle\Console\NotificationCommandInterface;
use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

class JmsJobBundleProvider implements JobProviderInterface
{
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createJob(NotificationMessageInterface $notification): void
    {
        $job = new Job(NotificationCommandInterface::SEND_NOTIFICATION, [$notification->getId()]);
        $this->entityManager->persist($job);
    }
}

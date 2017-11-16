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
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $queue;

    public function __construct(EntityManager $entityManager, string $queue)
    {
        $this->entityManager = $entityManager;
        $this->queue         = $queue;
    }

    public function createJob(NotificationMessageInterface $notification): void
    {
        $job = new Job(NotificationCommandInterface::SEND_NOTIFICATION, [$notification->getId()], true, $this->queue);
        $this->entityManager->persist($job);
    }
}

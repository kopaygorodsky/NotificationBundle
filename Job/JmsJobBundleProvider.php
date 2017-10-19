<?php

namespace Kopaygorodsky\NotificationBundle\Job;

use Doctrine\ORM\EntityManager;
use JMS\JobQueueBundle\Entity\Job;
use Kopaygorodsky\NotificationBundle\Console\NotificationCommandInterface;
use Kopaygorodsky\NotificationBundle\Entity\NotificationMessageInterface;

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
        $this->entityManager->flush();
    }
}
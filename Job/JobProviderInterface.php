<?php

namespace Kopaygorodsky\NotificationBundle\Job;

use Kopaygorodsky\NotificationBundle\Entity\NotificationMessageInterface;

interface JobProviderInterface
{
    public function createJob(NotificationMessageInterface $notification): void;
}
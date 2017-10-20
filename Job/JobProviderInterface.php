<?php

namespace Kopay\NotificationBundle\Job;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

interface JobProviderInterface
{
    public function createJob(NotificationMessageInterface $notification): void;
}
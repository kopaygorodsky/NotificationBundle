<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Job;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

interface JobProviderInterface
{
    public function createJob(NotificationMessageInterface $notification): void;
}

<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Event;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

interface NotificationEventInterface
{
    const NOTIFICATION_CREATED      = 'kopaygorodsky.notification.message.created';
    const NOTIFICATION_POST_PERSIST = 'kopaygorodsky.notification.message.postPersist';
    const JOB_CREATED               = 'kopaygorodsky.notification.job.created';
    const JOB_PRE_SEND              = 'kopaygorodsky.notification.job.preSend';
    const JOB_POST_SEND             = 'kopaygorodsky.notification.job.postSend';
    const JOB_FAILED                = 'kopaygorodsky.notification.job.failed';

    public function getNotification(): NotificationMessageInterface;

    public function setNotification(NotificationMessageInterface $message): void;
}

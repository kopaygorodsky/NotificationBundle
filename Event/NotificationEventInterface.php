<?php

namespace Kopaygorodsky\NotificationBundle\Event;

use Kopaygorodsky\NotificationBundle\Entity\NotificationMessageInterface;

interface NotificationEventInterface
{
    const NOTIFICATION_CREATED = 'kopaygorodsky.notification.message.created';
    const NOTIFICATION_POST_PERSIST = 'kopaygorodsky.notification.message.postPersist';
    const JOB_CREATED = 'kopaygorodsky.notification.job.created';
    const JOB_PRE_SEND = 'kopaygorodsky.notification.job.preSend';
    const JOB_POST_SEND = 'kopaygorodsky.notification.job.postSend';

    public function getNotification(): NotificationMessageInterface;
    public function setNotification(NotificationMessageInterface $message): void;

}
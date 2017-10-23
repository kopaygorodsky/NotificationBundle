<?php

namespace Kopay\NotificationBundle\Event;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

interface NotificationEventInterface
{
    const NOTIFICATION_CREATED = 'kopay.notify.message.created';
    const NOTIFICATION_POST_PERSIST = 'kopay.notify.message.postPersist';
    const JOB_CREATED = 'kopay.notify.job.created';
    const JOB_PRE_SEND = 'kopay.notify.job.preSend';
    const JOB_POST_SEND = 'kopay.notify.job.postSend';
    const JOB_FAILED = 'kopay.notify.job.failed';

    public function getNotification(): NotificationMessageInterface;
    public function setNotification(NotificationMessageInterface $message): void;

}
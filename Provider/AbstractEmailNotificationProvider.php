<?php

namespace Kopay\NotificationBundle\Provider;

use Kopay\NotificationBundle\Entity\NotificationEmailInterface;
use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

abstract class AbstractEmailNotificationProvider extends AbstractNotificationProvider
{
    public function supports(NotificationMessageInterface $notification): bool
    {
        return $notification instanceof NotificationEmailInterface;
    }

    abstract protected function getReceiversEmails(NotificationEmailInterface $message): array;
}
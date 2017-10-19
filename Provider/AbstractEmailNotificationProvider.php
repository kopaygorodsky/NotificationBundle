<?php

namespace Kopaygorodsky\NotificationBundle\Provider;

use Kopaygorodsky\NotificationBundle\Entity\NotificationEmailInterface;
use Kopaygorodsky\NotificationBundle\Entity\NotificationMessageInterface;

abstract class AbstractEmailNotificationProvider extends AbstractNotificationProvider
{
    public function support(NotificationMessageInterface $notification): bool
    {
        return $notification instanceof NotificationEmailInterface;
    }

    abstract protected function getReceiversEmails(NotificationEmailInterface $message): array;
}
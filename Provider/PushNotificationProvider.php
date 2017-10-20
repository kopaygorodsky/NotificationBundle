<?php

namespace Kopay\NotificationBundle\Provider;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;
use Kopay\NotificationBundle\Entity\NotificationPush;

class PushNotificationProvider extends AbstractNotificationProvider
{
    public function send(NotificationMessageInterface $message): void
    {
        die('lol');
        // TODO: Implement send() method.
    }

    public function supports(NotificationMessageInterface $notification): bool
    {
        return $notification instanceof NotificationPush;
    }
}
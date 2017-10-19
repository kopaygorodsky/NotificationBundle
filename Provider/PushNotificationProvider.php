<?php

namespace Kopaygorodsky\NotificationBundle\Provider;

use Kopaygorodsky\NotificationBundle\Entity\NotificationMessageInterface;
use Kopaygorodsky\NotificationBundle\Entity\NotificationPush;

class PushNotificationProvider extends AbstractNotificationProvider
{
    public function send(NotificationMessageInterface $message): void
    {
        die('lol');
        // TODO: Implement send() method.
    }

    public function support(NotificationMessageInterface $notification): bool
    {
        return $notification instanceof NotificationPush;
    }
}
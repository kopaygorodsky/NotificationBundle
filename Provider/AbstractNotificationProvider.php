<?php

namespace Kopay\NotificationBundle\Provider;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

abstract class AbstractNotificationProvider implements NotificationProviderInterface
{
    abstract protected function getReceiversIdentity(NotificationMessageInterface $message): array;
}
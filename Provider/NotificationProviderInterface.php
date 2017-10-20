<?php

namespace Kopay\NotificationBundle\Provider;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

interface NotificationProviderInterface
{
    public function send(NotificationMessageInterface $message): void;
    public function support(NotificationMessageInterface $message): bool;
}
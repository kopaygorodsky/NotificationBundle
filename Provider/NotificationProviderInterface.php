<?php

namespace Kopaygorodsky\NotificationBundle\Provider;

use Kopaygorodsky\NotificationBundle\Entity\NotificationMessageInterface;

interface NotificationProviderInterface
{
    public function send(NotificationMessageInterface $message): void;
    public function support(NotificationMessageInterface $message): bool;
}
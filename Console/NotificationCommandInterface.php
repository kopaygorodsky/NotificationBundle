<?php

namespace Kopay\NotificationBundle\Console;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

interface NotificationCommandInterface
{
    const SEND_NOTIFICATION = 'notification:send';

    public function getNotification($id): NotificationMessageInterface;
}
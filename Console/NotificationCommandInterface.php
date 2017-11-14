<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Console;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

interface NotificationCommandInterface
{
    const SEND_NOTIFICATION = 'notification:send';

    public function getNotification($id): NotificationMessageInterface;
}

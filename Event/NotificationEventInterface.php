<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Event;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

interface NotificationEventInterface
{
    public const NOTIFICATION_CREATED      = 'kopay_notification.notification.post_created';
    public const NOTIFICATION_JOB_CREATED  = 'kopay_notification.job.post_created';
    public const NOTIFICATION_PRE_SEND     = 'kopay_notification.notification.pre_send';
    public const NOTIFICATION_POST_SEND    = 'kopay_notification.notification.post_send';
    public const NOTIFICATION_FAILED       = 'kopay_notification.notification.post_failed';

    public function getNotification(): NotificationMessageInterface;

    public function setNotification(NotificationMessageInterface $message): void;
}

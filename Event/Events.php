<?php

namespace Kopay\NotificationBundle\Event;

final class Events
{
    public const NOTIFICATION_CREATED      = 'kopay_notification.notification.post_created';
    public const NOTIFICATION_JOB_CREATED  = 'kopay_notification.job.post_created';
    public const NOTIFICATION_PRE_SEND     = 'kopay_notification.notification.pre_send';
    public const NOTIFICATION_POST_SEND    = 'kopay_notification.notification.post_send';
    public const NOTIFICATION_FAILED       = 'kopay_notification.notification.post_failed';
}
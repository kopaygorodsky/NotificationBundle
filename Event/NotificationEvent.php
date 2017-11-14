<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Event;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;
use Symfony\Component\EventDispatcher\Event;

class NotificationEvent extends Event implements NotificationEventInterface
{
    /**
     * @var NotificationMessageInterface
     */
    protected $notification;

    /**
     * NotificationEvent constructor.
     *
     * @param NotificationMessageInterface $notification
     */
    public function __construct(NotificationMessageInterface $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return NotificationMessageInterface
     */
    public function getNotification(): NotificationMessageInterface
    {
        return $this->notification;
    }

    /**
     * @param NotificationMessageInterface $notification
     */
    public function setNotification(NotificationMessageInterface $notification): void
    {
        $this->notification = $notification;
    }
}

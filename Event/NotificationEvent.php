<?php

namespace Kopaygorodsky\NotificationBundle\Event;

use Kopaygorodsky\NotificationBundle\Entity\NotificationMessageInterface;
use Symfony\Component\EventDispatcher\Event;

class NotificationEvent extends Event implements NotificationEventInterface
{
    /**
     * @var NotificationMessageInterface
     */
    protected $notification;

    /**
     * NotificationEvent constructor.
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
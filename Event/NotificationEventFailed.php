<?php

namespace Kopaygorodsky\NotificationBundle\Event;

use Kopaygorodsky\NotificationBundle\Entity\NotificationMessageInterface;
use Symfony\Component\EventDispatcher\Event;

class NotificationEventFailed extends NotificationEvent
{
    /**
     * @var \Exception
     */
    protected $exception;

    public function __construct(NotificationMessageInterface $notification, ? \Exception $exception)
    {
        parent::__construct($notification);
        $this->exception = $exception;
    }

    /**
     * @return \Exception
     */
    public function getException(): ? \Exception
    {
        return $this->exception;
    }

    /**
     * @param \Exception $exception
     */
    public function setException(? \Exception $exception): void
    {
        $this->exception = $exception;
    }
}
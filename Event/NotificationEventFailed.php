<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Event;

use Kopay\NotificationBundle\Entity\NotificationMessageInterface;

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

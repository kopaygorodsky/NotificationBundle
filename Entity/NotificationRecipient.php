<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Entity;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

class NotificationRecipient implements NotificationRecipientInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var NotificationMessageInterface
     */
    protected $notification;

    /**
     * @var UserInterface
     */
    protected $recipient;

    /**
     * @var bool
     */
    protected $seen = false;

    public function __construct(NotificationMessageInterface $notification, $recipient)
    {
        $this->id           = Uuid::uuid4();
        $this->notification = $notification;
        $this->recipient    = $recipient;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return NotificationMessageInterface
     */
    public function getNotification(): NotificationMessageInterface
    {
        return $this->notification;
    }

    /**
     * @return UserInterface
     */
    public function getRecipient(): UserInterface
    {
        return $this->recipient;
    }

    /**
     * @return bool
     */
    public function isSeen(): bool
    {
        return $this->seen;
    }

    /**
     * @param bool $seen
     */
    public function setSeen(bool $seen): void
    {
        $this->seen = $seen;
    }
}

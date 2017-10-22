<?php

namespace Kopay\NotificationBundle\Entity;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;

class NotificationRecipient implements NotificationRecipientInterface
{
    /**
     * @var int
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

    public function __construct()
    {
        $this->id = Uuid::uuid4();
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

    /**
     * @return UserInterface
     */
    public function getRecipient(): UserInterface
    {
        return $this->recipient;
    }

    /**
     * @param mixed $recipient
     */
    public function setRecipient(UserInterface $recipient): void
    {
        $this->recipient = $recipient;
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

    /**
     * @return int
     */
    public function getId(): ? int
    {
        return $this->id;
    }
}
<?php

namespace Kopay\NotificationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

abstract class Notification implements NotificationMessageInterface
{

    /**
     * @var int
     */
    protected $id;

    /**
     * @var Collection
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "You must specify at least one receiver",
     * )
     */
    protected $recipients;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var bool
     */
    protected $seen = false;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    public function __construct()
    {
        $this->recipients = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): ? int
    {
        return $this->id;
    }

    /**
     * @return Collection|UserInterface[]
     */
    public function getRecipients(): Collection
    {
        return $this->recipients;
    }

    /**
     * @return string
     */
    public function getMessage(): ? string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(? string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getTitle(): ? string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(? string $title): void
    {
        $this->title = $title;
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
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
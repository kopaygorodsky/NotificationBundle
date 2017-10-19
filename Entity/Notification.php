<?php

namespace Kopaygorodsky\NotificationBundle\Entity;

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

    public function __construct()
    {
        $this->recipients = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ? int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(? int $id): void
    {
        $this->id = $id;
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
}
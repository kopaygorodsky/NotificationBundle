<?php

namespace Kopay\NotificationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

abstract class Notification implements NotificationMessageInterface
{

    /**
     * @var string
     */
    protected $id;

    /**
     * @var Collection
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "You must specify at least one receiver",
     * )
     */
    protected $recipientsItems;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->recipientsItems = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Collection|NotificationRecipient[]
     */
    public function getRecipientsItems(): Collection
    {
        return $this->recipientsItems;
    }

    /**
     * @param NotificationRecipientInterface $notificationRecipient
     */
    public function addRecipientItem(NotificationRecipientInterface $notificationRecipient): void
    {
        $this->recipientsItems->add($notificationRecipient);
    }

    /**
     * @param NotificationRecipientInterface $notificationRecipient
     */
    public function removeRecipientItem(NotificationRecipientInterface $notificationRecipient): void
    {
        $this->recipientsItems->removeElement($notificationRecipient);
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
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param $recipient
     */
    public function addRecipient($recipient): void
    {
        $item = new NotificationRecipient();
        $item->setRecipient($recipient);
        $this->addRecipientItem($item);
    }
}
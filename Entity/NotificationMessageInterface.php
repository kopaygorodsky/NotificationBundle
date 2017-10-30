<?php

  namespace Kopay\NotificationBundle\Entity;

  use Doctrine\Common\Collections\Collection;

  interface NotificationMessageInterface
  {
    public function getId();

    /**
     * @return Collection|NotificationRecipient[]
     */
    public function getRecipientsItems(): Collection;

    /**
     * Get text message
     *
     * @return null|string
     */
    public function getMessage(): ? string;

    /**
     * @param null|string $message
     */
    public function setMessage(?string $message): void;

    /**
     * Get title/subject of notification
     *
     * @return null|string
     */
    public function getTitle(): ?string;

    /**
     * @param null|string $title
     */
    public function setTitle(?string $title): void;
  }
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
     * Get title/subject of notification
     *
     * @return null|string
     */
    public function getTitle(): ? string;
}
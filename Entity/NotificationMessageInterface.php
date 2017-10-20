<?php

namespace Kopay\NotificationBundle\Entity;

use Doctrine\Common\Collections\Collection;

interface NotificationMessageInterface
{
    public function getId();
    public function getRecipients(): Collection;
    public function getMessage(): ? string;
    public function getTitle(): ? string;
    public function isSeen(): bool;
}
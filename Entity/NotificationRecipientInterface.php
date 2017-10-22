<?php

namespace Kopay\NotificationBundle\Entity;

interface NotificationRecipientInterface
{
    public function isSeen(): bool;
    public function setSeen(bool $seen): void;
    public function getRecipient();
    public function getNotification(): NotificationMessageInterface;
}
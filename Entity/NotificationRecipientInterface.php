<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Entity;

interface NotificationRecipientInterface
{
    public function isSeen(): bool;

    public function setSeen(bool $seen): void;

    public function getRecipient();

    public function getNotification(): NotificationMessageInterface;
}

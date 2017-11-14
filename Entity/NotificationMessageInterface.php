<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * Get text message.
     *
     * @return null|string
     */
    public function getMessage(): ? string;

    /**
     * Get title/subject of notification.
     *
     * @return null|string
     */
    public function getTitle(): ? string;
}

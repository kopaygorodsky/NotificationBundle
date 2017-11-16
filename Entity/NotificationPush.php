<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Entity;

class NotificationPush extends Notification
{
    /**
     * @var array
     */
    protected $payload;

    public function __construct(string $title, string $message, ? array $payload, bool $visible, array $recipients)
    {
        parent::__construct($title, $message, $visible, $recipients);
        $this->payload = $payload;
    }

    /**
     * @return array
     */
    public function getPayload(): ? array
    {
        return $this->payload;
    }
}

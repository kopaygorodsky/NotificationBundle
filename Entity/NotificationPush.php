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
    protected $value;

    public function __construct(string $title, string $message, array $value, array $recipients)
    {
        parent::__construct($title, $message, $recipients);
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getValue(): ? array
    {
        return $this->value;
    }
}

<?php

/*
 * This file is part of the KopayNotificationBundle package.
 * (c) kopaygorodsky
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kopay\NotificationBundle\Provider\ReceiverIdentity;

class EmailReceiverIdentity implements ReceiverIdentityInterface
{
    public function getIdentity($receiver): string
    {
        return $receiver->getEmail();
    }

    public function getIdentities(array $receivers): array
    {
        return array_map(function ($receiver) {
            return $this->getIdentity($receiver);
        }, $receivers);
    }
}
